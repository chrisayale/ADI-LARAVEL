<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default SACCO Settings
    |--------------------------------------------------------------------------
    |
    | These settings define the default values for newly created SACCOs.
    |
    */

    'default' => [
        'name' => env('SACCO_DEFAULT_NAME', 'Default SACCO'),
        'code' => env('SACCO_DEFAULT_CODE', 'DFLT'),
        'registration_number' => env('SACCO_DEFAULT_REGISTRATION_NUMBER', null),
        'phone' => env('SACCO_DEFAULT_PHONE', null),
        'email' => env('SACCO_DEFAULT_EMAIL', null),
        'address' => env('SACCO_DEFAULT_ADDRESS', null),
        'primary_currency' => env('SACCO_DEFAULT_PRIMARY_CURRENCY', 'CDF'),
        'secondary_currency' => env('SACCO_DEFAULT_SECONDARY_CURRENCY', 'CDF'),
        'status' => 'active',
        'settings' => [
            'interest_rate' => env('SACCO_DEFAULT_INTEREST_RATE', 0.05),
            'loan_term_months' => env('SACCO_DEFAULT_LOAN_TERM_MONTHS', 12),
            'min_share_amount' => env('SACCO_DEFAULT_MIN_SHARE_AMOUNT', 10000),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SACCO Validation Rules
    |--------------------------------------------------------------------------
    |
    | These rules are used when validating SACCO data.
    |
    */

    'validation' => [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:20|unique:sacos,code',
        'registration_number' => 'nullable|string|max:100|unique:sacos,registration_number',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string',
        'primary_currency' => 'required|string|max:3',
        'secondary_currency' => 'required|string|max:3',
        'status' => 'required|in:active,inactive,suspended',
        'settings' => 'nullable|array',
    ],

    /*
    |--------------------------------------------------------------------------
    | SACCO Session Key
    |--------------------------------------------------------------------------
    |
    | This key is used to store the selected SACCO ID in the session for Super Admin users.
    |
    */

    'session_key' => 'selected_sacco_id',

];