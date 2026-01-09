<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add indexes to product_details table for better query performance
        Schema::table('product_details', function (Blueprint $table) {
            // Composite index for most common query: where product_id AND is_sold
            // This will speed up: ProductDetail::where('product_id', $id)->where('is_sold', 0)->get()
            if (!$this->indexExists('product_details', 'product_details_product_id_is_sold_index')) {
                $table->index(['product_id', 'is_sold'], 'product_details_product_id_is_sold_index');
            }
            
            // Index on product_id alone (if not exists)
            if (!$this->indexExists('product_details', 'product_details_product_id_index')) {
                $table->index('product_id', 'product_details_product_id_index');
            }
            
            // Index on is_sold alone for filtering sold/unsold items
            if (!$this->indexExists('product_details', 'product_details_is_sold_index')) {
                $table->index('is_sold', 'product_details_is_sold_index');
            }
        });

        // Optimize asset_logs table queries
        Schema::table('asset_logs', function (Blueprint $table) {
            // Composite index for query: where asset_id AND asset_detail_id IN (...)
            if (!$this->indexExists('asset_logs', 'asset_logs_asset_id_detail_id_index')) {
                $table->index(['asset_id', 'asset_detail_id'], 'asset_logs_asset_id_detail_id_index');
            }
            
            // Index on created_at for date filtering in listLogs
            if (!$this->indexExists('asset_logs', 'asset_logs_created_at_index')) {
                $table->index('created_at', 'asset_logs_created_at_index');
            }
        });

        // Add indexes to deposits table for SEO API queries
        if (Schema::hasTable('deposits')) {
            Schema::table('deposits', function (Blueprint $table) {
                // Index for common filters in listAnalytics
                if (!$this->indexExists('deposits', 'deposits_status_created_at_index')) {
                    $table->index(['status', 'created_at'], 'deposits_status_created_at_index');
                }
                
                // Index on method_code for filtering
                if (!$this->indexExists('deposits', 'deposits_method_code_index')) {
                    $table->index('method_code', 'deposits_method_code_index');
                }
                
                // Index on user_id for user-specific queries
                if (!$this->indexExists('deposits', 'deposits_user_id_index')) {
                    $table->index('user_id', 'deposits_user_id_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->dropIndex('product_details_product_id_is_sold_index');
            $table->dropIndex('product_details_product_id_index');
            $table->dropIndex('product_details_is_sold_index');
        });

        Schema::table('asset_logs', function (Blueprint $table) {
            $table->dropIndex('asset_logs_asset_id_detail_id_index');
            $table->dropIndex('asset_logs_created_at_index');
        });

        if (Schema::hasTable('deposits')) {
            Schema::table('deposits', function (Blueprint $table) {
                $table->dropIndex('deposits_status_created_at_index');
                $table->dropIndex('deposits_method_code_index');
                $table->dropIndex('deposits_user_id_index');
            });
        }
    }

    /**
     * Check if an index exists on a table
     */
    private function indexExists($table, $indexName)
    {
        $connection = Schema::getConnection();
        $databaseName = $connection->getDatabaseName();
        
        $result = DB::select(
            "SELECT COUNT(*) as count 
             FROM information_schema.statistics 
             WHERE table_schema = ? 
             AND table_name = ? 
             AND index_name = ?",
            [$databaseName, $table, $indexName]
        );
        
        return $result[0]->count > 0;
    }
};
