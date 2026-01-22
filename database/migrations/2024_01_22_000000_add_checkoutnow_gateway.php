<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCheckoutNowGateway extends Migration
{
    public function up()
    {
        // Insert CheckoutNow gateway
        DB::table('gateways')->insert([
            'code' => 121,
            'name' => 'CheckoutNow',
            'alias' => 'CheckoutNow',
            'status' => 1,
            'gateway_parameters' => json_encode([
                'api_key' => [
                    'title' => 'API Key',
                    'global' => true,
                    'value' => ''
                ]
            ]),
            'supported_currencies' => json_encode(['NGN' => ['symbol' => '₦']]),
            'crypto' => 0,
            'description' => 'CheckoutPay Payment Gateway (CheckoutNow)',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insert gateway currency
        DB::table('gateway_currencies')->insert([
            'name' => 'CheckoutNow - NGN',
            'gateway_alias' => 'CheckoutNow',
            'currency' => 'NGN',
            'symbol' => '₦',
            'method_code' => 121,
            'min_amount' => 100,
            'max_amount' => 1000000,
            'percent_charge' => 1.0,
            'fixed_charge' => 50,
            'rate' => 1,
            'gateway_parameter' => json_encode([
                'api_key' => ''
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        DB::table('gateways')->where('code', 121)->delete();
        DB::table('gateway_currencies')->where('method_code', 121)->delete();
    }
}
