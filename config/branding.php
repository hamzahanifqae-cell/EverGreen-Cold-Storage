<?php

return [

    'name' => 'EverGreen Cold Storage',

    'logo' => 'images/evergreen-logo.svg',

    'logo_dark' => 'images/evergreen-logo-dark.svg',

    'icon' => 'images/evergreen-icon.svg',

    'favicon' => 'favicon.svg',

    'login_visual' => 'images/evergreen-login-visual.svg',

    'primary_merchant_email' => env('PRIMARY_MERCHANT_EMAIL', 'info@evergreen.com'),

    'primary_merchant_website' => env('PRIMARY_MERCHANT_WEBSITE', 'https://flowdesk.app'),

    /*
    | Phone number for the "Contact us" button on the marketing site.
    | Leave empty until it is known; the button then scrolls to the footer.
    */
    'contact_phone' => env('CONTACT_PHONE'),

    'legacy_merchant_email' => 'info@zgngreenpvt.com',

    'colors' => [
        'primary' => '#0f766e',
        'secondary' => '#64748b',
        'accent' => '#10b981',
        'sidebar_dark' => '#0a0a0a',
        'shell_bg' => '#000000',
        'card_bg' => '#0b0f14',
        'success' => '#22c55e',
        'danger' => '#dc2626',
        'warning' => '#f59e0b',
        'default' => '#1e293b',
    ],

    /*
    | Branding shown to "Try demo" visitors from the marketing site, so the demo
    | matches the Cold Storage CRM landing page. Keys mirror the ones above;
    | anything not set here falls back to the normal branding.
    */
    'demo' => [
        'name' => 'Cold Storage CRM',
        'logo' => 'images/cold-storage-crm-logo.svg',
        'logo_dark' => 'images/cold-storage-crm-logo-dark.svg',
        'favicon' => 'images/cold-storage-crm-icon.svg',
        'colors' => [
            'primary' => '#0b6bcb',
            'secondary' => '#64748b',
            'accent' => '#38bdf8',
            'success' => '#22c55e',
            'danger' => '#dc2626',
            'warning' => '#f59e0b',
            'default' => '#1e293b',
        ],
        'sidebar' => [
            'gradient_start' => '#38bdf8',
            'gradient_mid' => '#0b6bcb',
            'gradient_end' => '#0ea5e9',
        ],
    ],

    'sidebar' => [
        'background' => '#0a0a0a',
        'surface' => '#111827',
        'text' => '#e2e8f0',
        'muted' => '#94a3b8',
        'active' => '#14b8a6',
        'gradient_start' => '#10b981',
        'gradient_mid' => '#0f766e',
        'gradient_end' => '#14b8a6',
        'icon' => '#94a3b8',
        'header' => '#64748b',
    ],

];
