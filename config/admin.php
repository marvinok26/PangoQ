<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the admin panel.
    |
    */

    'name' => env('ADMIN_NAME', 'Admin Panel'),
    'logo' => env('ADMIN_LOGO', '/images/logo.png'),
    
    /*
    |--------------------------------------------------------------------------
    | Admin Roles & Permissions
    |--------------------------------------------------------------------------
    */
    'roles' => [
        'super_admin' => [
            'label' => 'Super Admin',
            'permissions' => ['*'] // All permissions
        ],
        'admin' => [
            'label' => 'Admin',
            'permissions' => [
                'manage_users',
                'manage_trips',
                'manage_wallets',
                'view_analytics',
                'manage_transactions'
            ]
        ],
        'moderator' => [
            'label' => 'Moderator',
            'permissions' => [
                'view_users',
                'moderate_trips',
                'view_analytics'
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard Settings
    |--------------------------------------------------------------------------
    */
    'dashboard' => [
        'items_per_page' => 15,
        'recent_activities_count' => 10,
        'cache_stats_minutes' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Activity Logging
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => true,
        'retention_days' => 90,
        'log_ip' => true,
        'log_user_agent' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [
        'session_timeout' => 120, // minutes
        'max_login_attempts' => 5,
        'lockout_duration' => 30, // minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    */
    'uploads' => [
        'max_file_size' => 10240, // KB
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
        'path' => 'admin/uploads',
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Settings
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'admin_email' => env('ADMIN_EMAIL', 'admin@example.com'),
        'send_alerts' => true,
        'alert_threshold' => [
            'failed_transactions' => 10,
            'flagged_content' => 5,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Settings
    |--------------------------------------------------------------------------
    */
    'theme' => [
        'sidebar_color' => 'dark',
        'navbar_color' => 'light',
        'accent_color' => '#667eea',
    ],
];