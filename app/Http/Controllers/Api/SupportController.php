<?php

namespace App\Http\Controllers\Api;

use App\Models\SupportRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    /**
     * Store a new support request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'nullable|string|max:50',
            'subject'  => 'required|string|max:255',
            'order_id' => 'nullable|string|max:255',
            'message'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation échouée',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            SupportRequest::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'order_id' => $request->order_id,
                'message' => $request->message,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Votre demande de support a été envoyée avec succès. Nous vous contacterons dans les 24-48 heures.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite lors de l\'envoi de votre demande. Veuillez réessayer.'
            ], 500);
        }
    }
}
