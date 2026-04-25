<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Brevo Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Brevo (formerly Sendinblue) API and SMTP
    |
    */

    'api_key' => env('BREVO_API_KEY'),

    'smtp' => [
        'host' => env('MAIL_HOST', 'smtp-relay.brevo.com'),
        'port' => env('MAIL_PORT', 587),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    ],

    'default_sender' => [
        'email' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Your Store'),
    ],

    // Transactional email settings
    'transactional' => [
        'order_confirmation' => [
            'template_id' => env('BREVO_TEMPLATE_ORDER_CONFIRMATION', 1),
            'active' => true,
        ],
        'order_status_update' => [
            'template_id' => env('BREVO_TEMPLATE_ORDER_STATUS', 2),
            'active' => true,
        ],
        'welcome' => [
            'template_id' => env('BREVO_TEMPLATE_WELCOME', 3),
            'active' => true,
        ],
    ],

    // Marketing email settings
    'marketing' => [
        'list_ids' => [
            'newsletter' => env('BREVO_NEWSLETTER_LIST_ID', 1),
            'customers' => env('BREVO_CUSTOMERS_LIST_ID', 2),
        ],
    ],

    // Webhook settings
    'webhook' => [
        'secret' => env('BREVO_WEBHOOK_SECRET'),
        'url' => env('APP_URL') . '/webhooks/brevo',
    ],

    // Enable debug mode
    'debug' => env('BREVO_DEBUG', false),
];
