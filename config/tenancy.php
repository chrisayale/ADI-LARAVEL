<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tenant Middleware Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the tenant middleware that resolves the current SACCO context.
    |
    */

    'middleware' => [

        /*
        |--------------------------------------------------------------------------
        | Skip Routes
        |--------------------------------------------------------------------------
        |
        | These routes will be skipped by the tenant middleware, useful for
        | public routes like authentication, registration, etc.
        |
        */

        'skip' => [
            'login',
            'logout',
            'password.*',
            'register',
            'verify-email',
            'password-reset',
            'password/confirm',
            'super-admin/sacco-context', // Super Admin SACCO context switching route
        ],

        /*
        |--------------------------------------------------------------------------
        | Error Messages
        |--------------------------------------------------------------------------
        |
        | Custom error messages for tenant-related exceptions.
        |
        */

        'messages' => [
            'sacco_not_found' => 'SACCO avec l\'ID :id introuvable.',
            'sacco_inactive' => 'Le SACCO :id est inactif (status: :status).',
            'no_sacco_assigned' => 'Aucun SACCO assigné à l\'utilisateur.',
            'only_super_admin_can_set_context' => 'Seul un Super Admin peut changer le contexte SACCO.',
            'internal_error' => 'Une erreur interne est survenue. Veuillez vous reconnecter.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tenant Context Cache
    |--------------------------------------------------------------------------
    |
    | Configuration for caching the tenant context to improve performance.
    |
    */

    'cache' => [

        /*
        |--------------------------------------------------------------------------
        | Enable Context Caching
        |--------------------------------------------------------------------------
        |
        | Determines whether to cache the tenant context.
        |
        */

        'enabled' => env('TENANCY_CACHE_ENABLED', false),

        /*
        |--------------------------------------------------------------------------
        | Cache TTL
        |--------------------------------------------------------------------------
        |
        | The number of seconds to cache the tenant context.
        |
        */

        'ttl' => env('TENANCY_CACHE_TTL', 300), // 5 minutes

        /*
        |--------------------------------------------------------------------------
        | Cache Key Prefix
        |--------------------------------------------------------------------------
        |
        | The prefix used for tenant context cache keys.
        |
        */

        'prefix' => 'tenancy_context',

    ],

    /*
    |--------------------------------------------------------------------------
    | Super Admin SACCO Assignment
    |--------------------------------------------------------------------------
    |
    | Configuration for how Super Admin users are assigned to SACCOs.
    |
    */

    'super_admin' => [

        /*
        |--------------------------------------------------------------------------
        | Default Behavior
        |--------------------------------------------------------------------------
        |
        | Whether Super Admin users should have a default SACCO assignment.
        | If false, Super Admin users must explicitly select a SACCO context.
        |
        */

        'has_default_sacco' => env('SUPER_ADMIN_HAS_DEFAULT_SACCO', false),

        /*
        |--------------------------------------------------------------------------
        | Default SACCO ID
        |--------------------------------------------------------------------------
        |
        | The default SACCO ID for Super Admin users if has_default_sacco is true.
        |
        */

        'default_sacco_id' => env('SUPER_ADMIN_DEFAULT_SACCO_ID', null),

    ],

];