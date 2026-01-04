<?php

namespace App\Http\Controllers\Api\Git;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\AssetLog;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssetController extends Controller
{
    /**
     * Retrieve assets (Pull products)
     */
    public function retrieveAssets(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate request
            $request->validate([
                'asset_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1|max:1000',
                'action' => 'required|in:archive,remove',
                'processed_by' => 'nullable|string|max:255',
            ]);

            $productId = $request->asset_id;
            $quantity = $request->quantity;
            $action = $request->action; // 'archive' or 'remove'
            $processedBy = $request->processed_by ?? 'system';

            // Get product
            $product = Product::findOrFail($productId);

            // Get available unsold product details
            $availableStock = ProductDetail::where('product_id', $productId)
                ->where('is_sold', Status::NO)
                ->count();

            if ($availableStock < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock. Only {$availableStock} available."
                ], 400);
            }

            // Get unsold product details (limit by quantity)
            $productDetails = ProductDetail::where('product_id', $productId)
                ->where('is_sold', Status::NO)
                ->orderBy('id', 'asc')
                ->limit($quantity)
                ->get();

            $retrievedDetails = [];
            $assetLogs = [];

            foreach ($productDetails as $productDetail) {
                // Store product detail data BEFORE processing (important for delete)
                $detailId = $productDetail->id;
                $detailData = $productDetail->details ?? null;
                $detailCreatedAt = $productDetail->created_at->toDateTimeString();
                
                $assetData = [
                    'product_detail_id' => $detailId,
                    'product_id' => $productDetail->product_id,
                    'details' => $detailData,
                    'created_at' => $detailCreatedAt,
                ];

                if ($action === 'archive') {
                    // Mark as sold (archive)
                    $productDetail->is_sold = Status::YES;
                    $productDetail->save();
                    $status = 'archived';
                } else {
                    // Remove (delete) - delete first, then log
                    $productDetail->delete();
                    $status = 'removed';
                }

                // Create asset log entry
                $assetLog = AssetLog::create([
                    'asset_id' => $productId,
                    'asset_detail_id' => $detailId,
                    'processed_by' => $processedBy,
                    'process_type' => $action,
                    'asset_data' => $assetData,
                ]);

                $retrievedDetails[] = [
                    'id' => $detailId,
                    'product_id' => $productId,
                    'product_name' => $product->name,
                    'product_amount' => (float) $product->price,
                    'details' => $detailData,
                    'status' => $status,
                ];

                $assetLogs[] = $assetLog->id;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$quantity} assets retrieved successfully",
                'data' => [
                    'asset_id' => $productId,
                    'asset_name' => $product->name,
                    'asset_amount' => (float) $product->price,
                    'retrieved_count' => count($retrievedDetails),
                    'asset_details' => $retrievedDetails,
                ],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Git API retrieveAssets error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving assets: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List products with available stock
     */
    public function listProducts(Request $request)
    {
        try {
            $query = Product::withCount([
                'productDetails as available_stock_count' => function($q) {
                    $q->where('is_sold', Status::NO);
                }
            ]);

            // Apply filters
            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->has('status') && $request->status !== null) {
                $query->where('status', $request->status);
            }

            if ($request->has('min_stock') && $request->min_stock) {
                // Filter products with at least this much stock
                $minStock = (int) $request->min_stock;
                $query->having('available_stock_count', '>=', $minStock);
            }

            // Only show products with available stock (optional filter)
            if ($request->has('only_in_stock') && $request->only_in_stock == true) {
                $query->having('available_stock_count', '>', 0);
            }

            // Pagination
            $limit = min($request->get('limit', 50), 500); // Max 500 per page
            $page = max($request->get('page', 1), 1);
            
            $products = $query->orderBy('id', 'desc')->paginate($limit, ['*'], 'page', $page);

            // Format response
            $data = $products->map(function ($product) {
                return [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_amount' => (float) $product->price,
                    'category_id' => $product->category_id,
                    'status' => $product->status,
                    'available_stock' => (int) ($product->available_stock_count ?? 0), // Number of unsold items
                    'description' => $product->description ?? null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'products' => $data,
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                        'total_pages' => $products->lastPage(),
                    ],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving products'
            ], 500);
        }
    }

    /**
     * List asset logs
     */
    public function listLogs(Request $request)
    {
        try {
            $query = AssetLog::with(['product', 'productDetail']);

            // Apply filters
            if ($request->has('asset_id') && $request->asset_id) {
                $query->where('asset_id', $request->asset_id);
            }

            if ($request->has('process_type') && $request->process_type) {
                $query->where('process_type', $request->process_type);
            }

            if ($request->has('processed_by') && $request->processed_by) {
                $query->where('processed_by', $request->processed_by);
            }

            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Pagination
            $limit = min($request->get('limit', 50), 500); // Max 500 per page
            $page = max($request->get('page', 1), 1);
            
            $logs = $query->orderBy('id', 'desc')->paginate($limit, ['*'], 'page', $page);

            // Format response
            $data = $logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'asset_id' => $log->asset_id,
                    'asset_name' => $log->product->name ?? null,
                    'asset_detail_id' => $log->asset_detail_id,
                    'process_type' => $log->process_type,
                    'processed_by' => $log->processed_by,
                    'asset_data' => $log->asset_data,
                    'created_at' => $log->created_at->toDateTimeString(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'logs' => $data,
                    'pagination' => [
                        'current_page' => $logs->currentPage(),
                        'per_page' => $logs->perPage(),
                        'total' => $logs->total(),
                        'total_pages' => $logs->lastPage(),
                    ],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving asset logs'
            ], 500);
        }
    }
}

