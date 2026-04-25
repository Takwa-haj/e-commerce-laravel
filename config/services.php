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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    'currency' => env('STRIPE_CURRENCY', 'EUR'),
],


    'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => '/auth/google/callback',
],
'brevo' => [
        'api_key' => env('BREVO_API_KEY'),
        'templates' => [
            'order_confirmation' => env('BREVO_TEMPLATE_ORDER_CONFIRMATION', 1),
            'order_status_update' => env('BREVO_TEMPLATE_ORDER_STATUS_UPDATE', 2),
            'order_invoice' => env('BREVO_TEMPLATE_ORDER_INVOICE', 3),
            'order_summary' => env('BREVO_TEMPLATE_ORDER_SUMMARY', 4),
            'order_cancelled' => env('BREVO_TEMPLATE_ORDER_CANCELLED', 5),
            'refund_request_confirmation' => env('BREVO_TEMPLATE_REFUND_REQUEST_CONFIRMATION', 6),
            'refund_status_update' => env('BREVO_TEMPLATE_REFUND_STATUS_UPDATE', 7),
            'refund_credit_note' => env('BREVO_TEMPLATE_REFUND_CREDIT_NOTE', 8),
            'welcome' => env('BREVO_TEMPLATE_WELCOME', 9),
            'password_reset' => env('BREVO_TEMPLATE_PASSWORD_RESET', 10),
            'admin_new_order' => env('BREVO_TEMPLATE_ADMIN_NEW_ORDER', 11),
            'admin_new_refund' => env('BREVO_TEMPLATE_ADMIN_NEW_REFUND', 12),
            'admin_custom' => env('BREVO_TEMPLATE_ADMIN_CUSTOM', 13),
            'test' => env('BREVO_TEMPLATE_TEST', 14),
        ],
    ],

'mailtrap' => [
   'api_token' => env('MAILTRAP_API_TOKEN'),
   'inbox_id' => env('MAILTRAP_INBOX_ID'),
    ],
'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],
];
