<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Support\Facades\DB;

class PushNotificationController extends Controller
{
    
    public function sendPushNotification(Request $request)
    {
        try {
            // Set Firebase credentials
            //$credentialsFilePath = "firebase_44/lovarddriverapp-firebase-adminsdk-fbsvc-fb7bc27406.json";
            $credentialsFilePath = "firebase_54_12/fcm.json";
            $client = new \Google_Client();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    
            // Firebase API URL
            $apiurl = 'https://fcm.googleapis.com/v1/projects/lovard-app/messages:send';
    
            // Refresh token and get access token
            $client->refreshTokenWithAssertion();
            $token = $client->getAccessToken();
            $access_token = $token['access_token'];
    
            $headers = [
                "Authorization: Bearer $access_token",
                'Content-Type: application/json'
            ];
    
            // FCM Payload
            $payload = [
                'message' => [
                    'token' => $request->fcm_token, // iOS requires "token" instead of "registration_ids"
                    
                    // ✅ Required for iOS: Notification will be displayed in system tray
                    'notification' => [
                        'title' => $request->title,
                        'body' => $request->description,
                    ],
    
                    // ✅ Custom Data for Flutter (Handled in app)
                    'data' => [
                        'title' => $request->title,
                        'description' => $request->description,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ],
    
                    // ✅ APNs settings for iOS
                    'apns' => [
                        'headers' => [
                            'apns-priority' => '10', // High priority
                        ],
                        'payload' => [
                            'aps' => [
                                'alert' => [
                                    'title' => $request->title,
                                    'body' => $request->description,
                                ],
                                'sound' => 'default', // Ensure sound is played
                                'badge' => 0, // Update app badge count
                                'content-available' => 1,
                            ],
                        ],
                    ],
                ]
            ];
    
            $payloadJson = json_encode($payload);
    
            // Send request via cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiurl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
    
            // Log the response for debugging
            Log::info("FCM Response: " . $response);
    
            // Check for errors
            if ($httpCode == 200) {
                return response()->json([
                    'success' => true,
                    'message' => 'Push notification sent successfully.',
                    'response' => json_decode($response, true)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send push notification.',
                    'response' => json_decode($response, true)
                ], $httpCode);
            }
        } catch (\Exception $e) {
            Log::error("FCM Error: " . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending the push notification.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


 
    public function sendBulkPushNotification($request)
    {
        try {
            // Set Firebase credentials
            //$credentialsFilePath = public_path('"firebase_44/lovarddriverapp-firebase-adminsdk-fbsvc-fb7bc27406.json');
            $credentialsFilePath = public_path('firebase_54_12/fcm.json');
            $client = new \Google_Client();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    
            // Firebase API URL
            $apiurl = 'https://fcm.googleapis.com/v1/projects/lovard-app/messages:send';
    
            // Refresh token and get access token
            $client->refreshTokenWithAssertion();
            $token = $client->getAccessToken();
            $access_token = $token['access_token'];
    
            $headers = [
                "Authorization: Bearer $access_token",
                'Content-Type: application/json'
            ];
    
            // Save notification to database
            DB::table('push_notifcation')->insert([
                'title' => $request['title'],
                'body' => $request['description'],
                'fcm_token' => $request['token'], // Ensure this value is correct
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            // FCM Payload
            $payload = [
                'message' => [
                    'token' => $request['token'], // iOS requires "token" instead of "registration_ids"
    
                    // ✅ Required for iOS: Notification will be displayed in system tray
                    'notification' => [
                        'title' => $request['title'],
                        'body' => $request['description'],
                    ],
    
                    // ✅ Custom Data for Flutter (Handled in app)
                    'data' => [
                        'title' => $request['title'],
                        'description' => $request['description'],
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ],
    
                    // ✅ APNs settings for iOS
                    'apns' => [
                        'headers' => [
                            'apns-priority' => '10', // High priority
                        ],
                        'payload' => [
                            'aps' => [
                                'alert' => [
                                    'title' => $request['title'],
                                    'body' => $request['description'],
                                ],
                                'sound' => 'default', // Ensure sound is played
                                'badge' => 0, // Update app badge count
                                'content-available' => 1,
                            ],
                        ],
                    ],
                ]
            ];
    
            $payloadJson = json_encode($payload);
    
            // Send request via cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiurl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
    
            // Log the response for debugging
            Log::info("Bulk FCM Response: " . $response);
    
            // Check for errors
            if ($httpCode == 200) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bulk push notifications sent successfully.',
                    'response' => json_decode($response, true)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send bulk push notifications.',
                    'response' => json_decode($response, true)
                ], $httpCode);
            }
        } catch (\Exception $e) {
            Log::error("Bulk FCM Error: " . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending bulk push notifications.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
