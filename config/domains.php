<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Split landing / app hosts (cPanel)
    |--------------------------------------------------------------------------
    |
    | Production split for EverGreen:
    | - LANDING_DOMAIN=evergreencold.store     → marketing landing only
    | - APP_DOMAIN=www.evergreencold.store     → full CRM (merchant + staff)
    | Leave blank locally so a single host continues to serve everything.
    |
    */

    'landing' => env('LANDING_DOMAIN'),

    'app' => env('APP_DOMAIN'),

    'landing_url' => env('LANDING_URL', env('LANDING_DOMAIN') ? 'https://'.env('LANDING_DOMAIN') : null),

    'app_url' => env('APP_URL', env('APP_DOMAIN') ? 'https://'.env('APP_DOMAIN') : null),

];
