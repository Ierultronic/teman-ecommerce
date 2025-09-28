<?php

return [
    /*
    |--------------------------------------------------------------------------
    | E-Invoice Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for LHDN Malaysia e-invoice compliance
    |
    */

    // Invoice numbering
    'invoice_prefix' => env('EINVOICE_PREFIX', 'INV'),

    // Seller Information (Your Business Details)
    'seller' => [
        'name' => env('EINVOICE_SELLER_NAME', 'Your Business Name'),
        'registration_number' => env('EINVOICE_SELLER_REG_NUMBER', ''),
        'tax_identification_number' => env('EINVOICE_SELLER_TAX_ID', ''),
        'sst_id' => env('EINVOICE_SELLER_SST_ID', ''),
        'msic_code' => env('EINVOICE_SELLER_MSIC_CODE', ''),
        'business_activity' => env('EINVOICE_SELLER_BUSINESS_ACTIVITY', 'Retail sale of products'),
        'address' => [
            'line1' => env('EINVOICE_SELLER_ADDRESS_LINE1', ''),
            'line2' => env('EINVOICE_SELLER_ADDRESS_LINE2', ''),
            'city' => env('EINVOICE_SELLER_CITY', ''),
            'state' => env('EINVOICE_SELLER_STATE', ''),
            'postal_code' => env('EINVOICE_SELLER_POSTAL_CODE', ''),
            'country' => env('EINVOICE_SELLER_COUNTRY', 'Malaysia'),
        ],
        'contact' => [
            'phone' => env('EINVOICE_SELLER_PHONE', ''),
            'email' => env('EINVOICE_SELLER_EMAIL', ''),
        ],
    ],

    // Buyer Information (Default values for individual customers)
    'buyer' => [
        'default_tin' => env('EINVOICE_BUYER_DEFAULT_TIN', ''),
        'default_id_number' => env('EINVOICE_BUYER_DEFAULT_ID_NUMBER', ''),
    ],

    // LHDN MyInvois Integration (for future API integration)
    'myinvois' => [
        'api_url' => env('MYINVOIS_API_URL', 'https://api.myinvois.hasil.gov.my'),
        'client_id' => env('MYINVOIS_CLIENT_ID', ''),
        'client_secret' => env('MYINVOIS_CLIENT_SECRET', ''),
        'environment' => env('MYINVOIS_ENVIRONMENT', 'sandbox'), // sandbox or production
    ],

    // Default tax settings
    'default_tax_rate' => env('EINVOICE_DEFAULT_TAX_RATE', 0), // 0% by default, can be configured
    'currency' => env('EINVOICE_CURRENCY', 'MYR'),

    // File generation settings
    'generate_xml' => env('EINVOICE_GENERATE_XML', true),
    'generate_json' => env('EINVOICE_GENERATE_JSON', true),
    'generate_pdf' => env('EINVOICE_GENERATE_PDF', true),
];
