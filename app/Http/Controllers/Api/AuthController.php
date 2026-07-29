<?php

namespace App\Http\Controllers\Api;

use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function createGuestUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'phone' => 'nullable|string|max:20',
            ]);

            $phone = $validated['phone'] ?? 'guest_' . uniqid();

            $user = User::firstOrCreate(
                ['phone' => $phone],
                ['fullname' => 'Guest', 'email' => null]
            );

            // Generate Sanctum token
            $token = $user->createToken('guest_token')->plainTextToken;

            return response()->json([
                'message' => 'Guest user created successfully',
                'user_id' => $user->id,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function refreshToken(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            // Invalidate old tokens (optional)
            $user->tokens()->delete();

            // Create new token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Token refreshed successfully',
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function registerPhone(Request $request)
    {
        try {
        $validated = $request->validate([
            'phone' => 'required|string|max:9',
        ]);

        $validated['phone']="0".$validated['phone'];
        // Check if the user already exists by phone
        $user = User::where('phone', $validated['phone'])->first();

        if (!$user) {
            // If user doesn't exist, create a new user
            $user = User::create([
                'phone' => $validated['phone'] , 'fullname' =>  '' 
            ]);
        }
        
        $validated['phone'] = preg_replace('/^0/', '', $validated['phone']);
        
        $existingOtp = Otp::where('user_id', $user->id)
            ->where('type', 'login')
            ->where('is_used', false)
            ->first();
        
        if ($existingOtp) {
            // Use the existing OTP
            $otp = $existingOtp->otp;
        } else {
            // Generate new OTP
            $otp = rand(100000, 999999);

            // Debug-only test phone: skip SMS and always return a known OTP.
            // Never active outside APP_DEBUG=true.
            if (config('app.debug') && $validated['phone'] == 123456789) {
                $otp = 111111;
            }

            // Create new OTP record
            Otp::updateOrCreate(
                ['user_id' => $user->id, 'type' => 'login'],
                ['is_used' => false, 'otp' => $otp]
            );
        }

        $countryPrefix = config('services.authentica.country_prefix', '+216');
        $phoneNumber   = $countryPrefix . $validated['phone'];
        $reversedOtp   = strrev($otp);

        // SMS delivery is optional — a missing AUTHENTICA_TOKEN disables it.
        // Useful during initial deployment before an SMS provider is wired up.
        $authenticaToken = config('services.authentica.token');
        if (!empty($authenticaToken)) {
            Http::withHeaders([
                'Content-Type'    => 'application/json',
                'X-Authorization' => $authenticaToken,
            ])->post(config('services.authentica.endpoint'), [
                'phone'       => $phoneNumber,
                'method'      => 'sms',
                'otp'         => $reversedOtp,
                'sender_name' => config('services.authentica.sender', 'BABASHOP'),
            ]);
        }

        $payload = [
            'message' => 'OTP sent',
            'user_id' => $user->id,
        ];
        // Only expose the OTP in the response when running in debug mode so
        // tests and local dev still work without a real SMS provider.
        if (config('app.debug')) {
            $payload['otp'] = $otp;
        }
        return response()->json($payload);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);

    }
    }
    
    public function checkToken(Request $request)
    {
       $version = 5;
        try {
            // First check if token is provided in request parameter
            $token = $request->token;
            // Log::info('User logged in', ['user_id' => $request->header('Authorization')]);

            // If no token in request, check Authorization header
            if (!$token) {
                $authHeader = $request->header('Authorization');
                if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
                    $token = substr($authHeader, 7); // Remove 'Bearer ' prefix
                } else {
                    $token = null;
                }
            }
            
            $img = config('services.placeholder.category_img', '');
            
            return response()->json([
                'is_valid' => true,
                'token' => $token,
                'V' => $version,
                'img' => $img
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'is_valid' => true,
                'token' => null,
                'V' => $version,
                'img' => config('services.placeholder.category_img', ''),
            ], 200);
        }
    }
    
    public function destroyAccount(Request $request)
    {
        try {
            // Check if the Sanctum token is valid and the user exists
            $user = $request->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Invalid token or user not found'], 401);
            }
    
            // Return success message if the token is valid and user exists
            return response()->json(['success' => true, 'message' => 'Account deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|integer',
        ]);

        // Check if the OTP is valid
        $otpRecord = Otp::where('user_id', $validated['user_id'])
            ->where('type', 'login')
            ->where('is_used', false)
            ->first();
        
        if (!$otpRecord) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }
        
        $storedOtp = $otpRecord->otp;
        $inputOtp = $validated['otp'];
        $reversedStoredOtp = strrev($storedOtp);
        $reversedInputOtp = strrev($inputOtp);
        
        // Log::info('OTP verification', [
        //     'stored' => $storedOtp,
        //     'input' => $inputOtp,
        //     'reversed_stored' => $reversedStoredOtp,
        //     'reversed_input' => $reversedInputOtp
        // ]);
        
        if ($storedOtp != $inputOtp && $reversedStoredOtp != $inputOtp && $storedOtp != $reversedInputOtp && $reversedStoredOtp != $reversedInputOtp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }        

        // Mark OTP as used
        $otpRecord->update(['is_used' => true]);

        // Generate Sanctum token for the user
        $user = User::find($validated['user_id']);
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $user->is_verified = true;
        $user->fcm_token = $request->fcm_token ?? null;
        $user->save();
        
        if($user->fullname == "" || $user->email == "")
        $redirect_to = '/update-profile';
        else
        $redirect_to = '/home'; 
        // Redirect to home page with token (or return token in JSON for front-end routing)
        $cleanedPhone = ltrim($user->phone, '0');
        
        return response()->json([
            'message' => 'Login successful, redirecting to homepage.',
            'token' => $token,
            'redirect_to' => $redirect_to,
            'user' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'phone' => $cleanedPhone,
                'fcm_token' => $user->fcm_token,
                'address' => $user->address,
                'longitude' => $user->longitude,
                'latitude' => $user->latitude,
                'birth_date' => $user->birth_date,
                'image' => $user->image,
                'is_verified' => $user->is_verified,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);

    }
   
}

    public function updateUserInfo(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'fullname' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'birth_date' => 'nullable|date',
                'longitude' => 'nullable|string|max:50',
                'latitude' => 'nullable|string|max:50',
                'fcm_token' => 'nullable|string|max:255',
            ]);

            // ✅ Find user
            $user = User::find($validated['user_id']);

            // ✅ Update all fields
            $user->fullname   = $validated['fullname'];
            $user->email      = $validated['email'] ?? $user->email;
            $user->address    = $validated['address'] ?? $user->address;
            $user->phone      = $validated['phone'] ?? $user->phone;
            $user->birth_date = $validated['birth_date'] ?? $user->birth_date;
            $user->longitude  = $validated['longitude'] ?? $user->longitude;
            $user->latitude   = $validated['latitude'] ?? $user->latitude;
            $user->fcm_token  = $validated['fcm_token'] ?? $user->fcm_token;

            $user->save();

            // ✅ Return complete user data (fresh from DB)
            return response()->json([
                'message' => 'User info updated successfully',
                'redirect_to' => '/home',
                'user' => [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'birth_date' => $user->birth_date,
                    'latitude' => $user->latitude,
                    'longitude' => $user->longitude,
                    'fcm_token' => $user->fcm_token,
                    'is_verified' => $user->is_verified,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating user info',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
      public function updateUserInfomations(Request $request)
    {
        try {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'fullname' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable',
            'birth_date' => 'nullable',
        ]);


        // Update user's name and email
        $user = User::find($validated['user_id']);
        $user->update([
            'email' => $validated['email'],
        ]);
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $fileExtension = strtolower($request->file('image')->getClientOriginalExtension());

            $imageName = time().'.'.$fileExtension;
            $image->move(public_path("uploads/user_img"), $imageName);
            $user->image = $imageName;
            $user->save();
        }
        
        if($validated['fullname']){
         $user->fullname = $validated['fullname'];
        }
        
        if($validated['birth_date']){
         $user->birth_date = $validated['birth_date'];
        }

        // if($validated['phone']){
        //  $user->phone = $validated['phone'];
        // }
        //  $user->is_verified = 0;
            $user->save();
      

        return response()->json(['message' => 'User info updated successfully' , 'user' => $user]);
        } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);

    }
    }

    public function getUserInfo(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            return response()->json([
                'success' => true,
                'user' => [
                    'id'         => $user->id,
                    'fullname'   => $user->fullname,
                    'email'      => $user->email,
                    'phone'      => $user->phone,
                    'address'    => $user->address,
                    'longitude'  => $user->longitude,
                    'latitude'   => $user->latitude,
                    'birth_date' => $user->birth_date,
                    'image'      => $user->image,
                    'is_verified'=> $user->is_verified,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Request an OTP delivered by email.
     *
     * Behavior:
     * - Does NOT create a user record. Only stores an OTP keyed on the email.
     * - Response includes `is_new_user` so the client can decide whether to
     *   route to signup vs login after verify.
     * - Rate limited to 3 requests / 15 min per email.
     */
    public function requestEmailOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);
        $email = strtolower(trim($validated['email']));

        $rateKey = 'email-otp-request:' . $email;
        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            $seconds = RateLimiter::availableIn($rateKey);
            return response()->json([
                'success' => false,
                'message' => 'Trop de demandes. Réessayez dans ' . $seconds . ' secondes.',
            ], 429);
        }
        RateLimiter::hit($rateKey, 900); // 15 minutes

        try {
            $existingUser = User::where('email', $email)->first();
            $isNewUser    = $existingUser === null;

            // Invalidate any prior unused email OTPs for this address.
            Otp::where('email', $email)
                ->where('channel', 'email')
                ->where('is_used', false)
                ->update(['is_used' => true]);

            $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            Otp::create([
                'user_id'    => $existingUser?->id,   // null for brand-new addresses
                'email'      => $email,
                'type'       => 'login',
                'channel'    => 'email',
                'otp'        => $otp,
                'is_used'    => false,
                'expires_at' => Carbon::now()->addMinutes(5),
                'attempts'   => 0,
            ]);

            try {
                Mail::to($email)->send(new OtpMail($otp, $existingUser));
            } catch (\Throwable $mailError) {
                Log::error('Email OTP send failed', [
                    'email' => $email,
                    'error' => $mailError->getMessage(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible d\'envoyer l\'e-mail pour le moment. Réessayez plus tard.',
                ], 503);
            }

            return response()->json([
                'success'     => true,
                'message'     => 'Code envoyé par e-mail.',
                'is_new_user' => $isNewUser,
                'expires_in'  => 300,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify an emailed OTP.
     *
     * On success:
     * - If no user exists for the email yet → create one with an empty
     *   fullname. `is_new_user=true` tells the client to route to the
     *   register/complete-profile step next.
     * - If a user exists → just issue a fresh token, `is_new_user=false`.
     */
    public function verifyEmailOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'otp'   => 'required|digits:6',
        ]);
        $email = strtolower(trim($validated['email']));

        try {
            $otpRecord = Otp::where('email', $email)
                ->where('channel', 'email')
                ->where('type', 'login')
                ->where('is_used', false)
                ->orderByDesc('id')
                ->first();

            if (!$otpRecord) {
                return response()->json(['success' => false, 'message' => 'Code invalide ou expiré.'], 400);
            }

            if ($otpRecord->isExpired()) {
                $otpRecord->update(['is_used' => true]);
                return response()->json(['success' => false, 'message' => 'Code expiré. Demandez-en un nouveau.'], 400);
            }

            if ($otpRecord->attempts >= 5) {
                $otpRecord->update(['is_used' => true]);
                return response()->json(['success' => false, 'message' => 'Trop de tentatives. Demandez un nouveau code.'], 429);
            }

            if ((string) $otpRecord->otp !== (string) $validated['otp']) {
                $otpRecord->increment('attempts');
                return response()->json(['success' => false, 'message' => 'Code incorrect.'], 400);
            }

            $otpRecord->update(['is_used' => true]);

            // Find OR create — first-time verifiers get their account
            // provisioned here, existing users just refresh their token.
            $user = User::where('email', $email)->first();
            $isNewUser = false;
            if (!$user) {
                $user = User::create([
                    'email'    => $email,
                    'fullname' => '',
                ]);
                $isNewUser = true;
            }
            $user->is_verified = true;
            $user->fcm_token   = $request->fcm_token ?? $user->fcm_token;
            $user->save();

            $token = $user->createToken('auth_token')->plainTextToken;

            $redirectTo = $isNewUser ? '/register' : '/home';

            return response()->json([
                'success'     => true,
                'message'     => 'Vérification réussie.',
                'token'       => $token,
                'is_new_user' => $isNewUser,
                'redirect_to' => $redirectTo,
                'user' => [
                    'id'          => $user->id,
                    'fullname'    => $user->fullname,
                    'email'       => $user->email,
                    'phone'       => $user->phone,
                    'fcm_token'   => $user->fcm_token,
                    'address'     => $user->address,
                    'longitude'   => $user->longitude,
                    'latitude'    => $user->latitude,
                    'birth_date'  => $user->birth_date,
                    'image'       => $user->image,
                    'is_verified' => $user->is_verified,
                    'created_at'  => $user->created_at,
                    'updated_at'  => $user->updated_at,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Revoke the current Sanctum token so the token stored in the app is
     * no longer accepted server-side.
     * Route: POST /api/auth/logout (auth:sanctum).
     */
    public function logout(Request $request)
    {
        $token = $request->user()?->currentAccessToken();
        if ($token) {
            $token->delete();
        }
        return response()->json(['success' => true]);
    }
}
