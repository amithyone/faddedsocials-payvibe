<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayVibeGateway extends Migration
{
    public function up()
    {
        // Insert PayVibe gateway
        DB::table('gateways')->insert([
            'code' => 120,
            'name' => 'PayVibe',
            'alias' => 'PayVibe',
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
            'description' => 'PayVibe Payment Gateway',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insert gateway currency
        DB::table('gateway_currencies')->insert([
            'name' => 'PayVibe - NGN',
            'gateway_alias' => 'PayVibe',
            'currency' => 'NGN',
            'symbol' => '₦',
            'method_code' => 120,
            'min_amount' => 100,
            'max_amount' => 1000000,
            'percent_charge' => 1.5,
            'fixed_charge' => 100,
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
        DB::table('gateways')->where('code', 120)->delete();
        DB::table('gateway_currencies')->where('method_code', 120)->delete();
    }
} 