<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetLog extends Model
{
    use HasFactory;

    protected $table = 'asset_logs';

    protected $fillable = [
        'asset_id',
        'asset_detail_id',
        'processed_by',
        'process_type',
        'asset_data',
    ];

    protected $casts = [
        'asset_data' => 'array',
    ];

    /**
     * Get the product (asset_id references products.id)
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'asset_id');
    }

    /**
     * Get the product detail (asset_detail_id references product_details.id)
     */
    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class, 'asset_detail_id');
    }
}


