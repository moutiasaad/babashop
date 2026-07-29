<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountDeletionRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AccountDeletionController extends Controller
{
    /**
     * Store a new account deletion request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation échouée',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if there's already a pending request for this email/phone
            $existingRequest = AccountDeletionRequest::where('email', $request->email)
                ->orWhere('phone', $request->phone)
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            if ($existingRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une demande de suppression est déjà en cours pour ce compte.'
                ], 400);
            }

            AccountDeletionRequest::create([
                'email' => $request->email,
                'phone' => $request->phone,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Votre demande de suppression de compte a été envoyée avec succès. Nous la traiterons dans les 48 heures.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite lors de l\'envoi de votre demande. Veuillez réessayer.'
            ], 500);
        }
    }
}
