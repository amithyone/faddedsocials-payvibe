<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayazaGateway extends Migration
{
    public function up()
    {
        // Insert Payaza gateway
        DB::table('gateways')->insert([
            'code' => 119,
            'name' => 'Payaza',
            'alias' => 'Payaza',
            'status' => 1,
            'gateway_parameters' => json_encode([
                'public_key' => [
                    'title' => 'Public Key',
                    'global' => true,
                    'value' => ''
                ],
                'secret_key' => [
                    'title' => 'Secret Key',
                    'global' => true,
                    'value' => ''
                ]
            ]),
            'supported_currencies' => json_encode(['NGN' => ['symbol' => '₦']]),
            'crypto' => 0,
            'description' => 'Payaza Payment Gateway',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insert gateway currency
        DB::table('gateway_currencies')->insert([
            'name' => 'Payaza - NGN',
            'gateway_alias' => 'Payaza',
            'currency' => 'NGN',
            'symbol' => '₦',
            'method_code' => 119,
            'min_amount' => 100,
            'max_amount' => 1000000,
            'percent_charge' => 0,
            'fixed_charge' => 0,
            'rate' => 1,
            'gateway_parameter' => json_encode([
                'public_key' => '',
                'secret_key' => ''
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        DB::table('gateways')->where('code', 119)->delete();
        DB::table('gateway_currencies')->where('method_code', 119)->delete();
    }
}
