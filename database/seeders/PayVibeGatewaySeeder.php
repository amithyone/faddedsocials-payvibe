<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayVibeGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if PayVibe gateway already exists
        $existingGateway = DB::table('gateways')->where('code', 120)->first();
        
        if (!$existingGateway) {
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
        }

        // Check if PayVibe gateway currency already exists
        $existingCurrency = DB::table('gateway_currencies')->where('method_code', 120)->first();
        
        if (!$existingCurrency) {
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
    }
} 