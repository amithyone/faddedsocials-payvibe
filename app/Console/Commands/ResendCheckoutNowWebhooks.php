<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Deposit;
use App\Models\User;
use App\Services\WebhookService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ResendCheckoutNowWebhooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:resend-checkoutnow {--hours=4 : Number of hours to look back}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend webhooks for successful CheckoutNow transactions that Xtrabusiness may have missed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $since = Carbon::now()->subHours($hours);
        
        $this->info("Looking for successful CheckoutNow transactions since {$since->toDateTimeString()}...");
        
        // Find all successful CheckoutNow deposits from the last X hours
        $deposits = Deposit::where('method_code', 121)
            ->where('status', 1) // Successful
            ->where('updated_at', '>=', $since)
            ->with('gateway')
            ->get();
        
        if ($deposits->isEmpty()) {
            $this->info("No successful CheckoutNow transactions found in the last {$hours} hours.");
            return 0;
        }
        
        $this->info("Found {$deposits->count()} successful CheckoutNow transaction(s).");
        $this->newLine();
        
        $successCount = 0;
        $failCount = 0;
        
        foreach ($deposits as $deposit) {
            $user = User::find($deposit->user_id);
            
            if (!$user) {
                $this->error("User not found for deposit ID: {$deposit->id}");
                $failCount++;
                continue;
            }
            
            $this->info("Processing deposit ID: {$deposit->id}, Transaction: {$deposit->trx}");
            
            try {
                // Refresh deposit to ensure gateway relationship is loaded
                $deposit->refresh();
                $deposit->load('gateway');
                
                // Send successful transaction webhook
                $result1 = WebhookService::sendSuccessfulTransaction($deposit, $user);
                
                // Send credited amount webhook
                $result2 = WebhookService::sendCreditedAmountToXtrabusiness($deposit, $user);
                
                if ($result1 && $result2) {
                    $this->info("  ✓ Webhooks sent successfully");
                    $successCount++;
                } else {
                    $this->warn("  ⚠ Some webhooks may have failed");
                    $failCount++;
                }
                
            } catch (\Exception $e) {
                $this->error("  ✗ Error: {$e->getMessage()}");
                Log::error('ResendCheckoutNowWebhooks error', [
                    'deposit_id' => $deposit->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $failCount++;
            }
            
            $this->newLine();
        }
        
        $this->info("=== Summary ===");
        $this->info("Total processed: {$deposits->count()}");
        $this->info("Successful: {$successCount}");
        $this->info("Failed: {$failCount}");
        
        return 0;
    }
}
