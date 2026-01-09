<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id'); // product_id (disguised)
            $table->unsignedBigInteger('asset_detail_id'); // product_detail_id (disguised)
            $table->string('processed_by')->nullable(); // pulled_by (disguised)
            $table->enum('process_type', ['archive', 'remove'])->default('archive'); // pull_type (disguised)
            $table->json('asset_data')->nullable(); // product_data (disguised)
            $table->timestamps();
            
            $table->index('asset_id');
            $table->index('asset_detail_id');
            
            $table->foreign('asset_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('asset_detail_id')->references('id')->on('product_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_logs');
    }
};
