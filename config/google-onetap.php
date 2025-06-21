<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Client ID
    |--------------------------------------------------------------------------
    |
    | This value is the client ID from your Google Console.
    | By default, it pulls from your services.google.client_id config
    |
    */
    'client_id' => env('GOOGLE_CLIENT_ID', config('services.google.client_id')),

    /*
    |--------------------------------------------------------------------------
    | Google Client Secret
    |--------------------------------------------------------------------------
    |
    | This value is the client secret from your Google Console.
    | By default, it pulls from your services.google.client_secret config
    |
    */
    'client_secret' => env('GOOGLE_CLIENT_SECRET', config('services.google.client_secret')),

    /*
    |--------------------------------------------------------------------------
    | Redirect path after authentication
    |--------------------------------------------------------------------------
    |
    | This value is the path where users will be redirected after authentication.
    |
    */
    'redirect' => 'dashboard',

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | This is the model that will be authenticated after a Google One Tap login
    |
    */
    'user_model' => \App\Models\User::class,
];
