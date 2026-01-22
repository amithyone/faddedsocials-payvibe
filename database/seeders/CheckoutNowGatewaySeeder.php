<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CheckoutNowGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if CheckoutNow gateway already exists
        $existingGateway = DB::table('gateways')->where('code', 121)->first();
        
        if (!$existingGateway) {
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
            
            $this->command->info('CheckoutNow gateway created successfully.');
        } else {
            $this->command->warn('CheckoutNow gateway already exists. Skipping gateway creation.');
        }

        // Check if CheckoutNow currency already exists
        $existingCurrency = DB::table('gateway_currencies')->where('method_code', 121)->first();
        
        if (!$existingCurrency) {
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
                'status' => 1, // Enable the gateway currency
                'gateway_parameter' => json_encode([
                    'api_key' => env('CHECKOUTNOW_API_KEY', '')
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $this->command->info('CheckoutNow currency configuration created successfully.');
        } else {
            // Update existing currency to ensure status is enabled
            DB::table('gateway_currencies')
                ->where('method_code', 121)
                ->update([
                    'status' => 1,
                    'updated_at' => now()
                ]);
            
            $this->command->warn('CheckoutNow currency already exists. Updated status to enabled.');
        }
        
        $this->command->info('CheckoutNow gateway seeder completed!');
        $this->command->info('Remember to set CHECKOUTNOW_API_KEY in your .env file.');
    }
}
