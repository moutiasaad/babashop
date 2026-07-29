<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Phone-OTP SMS gateway. Legacy (Authentica) is +966 only — kept
    // switchable so we can drop in a TN-compatible provider later.
    'authentica' => [
        'endpoint' => env('AUTHENTICA_ENDPOINT', 'https://api.authentica.sa/api/sdk/v1/sendOTP'),
        'token'    => env('AUTHENTICA_TOKEN'),
        'sender'   => env('AUTHENTICA_SENDER', 'BABASHOP'),
        'country_prefix' => env('AUTHENTICA_COUNTRY_PREFIX', '+216'),
    ],

    // Firebase Cloud Messaging (server-to-device push).
    // credentials_path is relative to public/.
    'firebase' => [
        'project_id'       => env('FIREBASE_PROJECT_ID'),
        'credentials_path' => env('FIREBASE_CREDENTIALS_PATH', 'firebase/fcm.json'),
    ],

    // Fallback placeholder image used by the legacy checkToken endpoint.
    'placeholder' => [
        'category_img' => env('PLACEHOLDER_CATEGORY_IMG', ''),
    ],

];
