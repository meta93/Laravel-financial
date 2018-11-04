<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 5/5/17
 * Time: 7:08 PM
 */

return[

    /*
    |--------------------------------------------------------------------------
    | Money $$$$
    |--------------------------------------------------------------------------
    |
    | Configure various options related to money. E.g VAT, default currency, etc
    |
    */
    'money' => [

        // Set the default currency
        'default_currency' => 'USD',

        'fiscal_year' => '2016-2017',

        // Set the VAT rate
        'VAT_RATE' => 0.10,

    ],

    'transaction' => [

        // Set the fiscal year

        'fiscal_year' => env('FISCAL_YEAR'),


    ],

    'company' => [

        // Set the fiscal year

        'comp_code' => '11001',


    ]
];