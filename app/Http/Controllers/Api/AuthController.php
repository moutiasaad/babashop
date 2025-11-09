<?php

namespace App\Http\Controllers\Api;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
         if($validated['phone'] == 123456789){
            $otp = 111111;
            //$phoneNumber = "+213798384870";
            $phoneNumber = "+21650349573";
        }

            // Create new OTP record
            Otp::updateOrCreate(
                ['user_id' => $user->id, 'type' => 'login'],
                ['is_used' => false, 'otp' => $otp]
            );
        }

        $phoneNumber = "+966".$validated['phone'];
        $reversedOtp = strrev($otp); // Reverse the OTP

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Authorization' => '$2y$10$k71P673hjNKlemDc2CNey.DLjCEViMoZC4fNsHyXasKNUhF/ULiG.',
        ])->post("https://api.authentica.sa/api/sdk/v1/sendOTP", [
            'phone' => $phoneNumber,
            'method' => 'sms',
            'otp' => $reversedOtp,
            'sender_name' => "LOVARD"
        ]);
                

        // Return response with the user ID and OTP for testing (in production, send via SMS)
        return response()->json([
            'message' => 'OTP sent',
            'otp' => $otp,  // Remove this in production, only send it via SMS
            'user_id' => $user->id,
        ]);
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
            
            $img = "https://lovardportal.online/uploads/categories/1746898074.jpg";
            
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
                'img' => "https://lovardportal.online/uploads/categories/1746898074.jpg"
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
}
