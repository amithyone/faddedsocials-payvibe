<?php

namespace App\Http\Controllers\Api\Seo;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeoController extends Controller
{
    /**
     * List analytics (Deposits) with filters
     */
    public function listAnalytics(Request $request)
    {
        try {
            $query = Deposit::with(['user', 'gateway']);

            // Apply filters
            if ($request->has('status') && $request->status !== null) {
                $query->where('status', $request->status);
            }

            if ($request->has('method_code') && $request->method_code) {
                $query->where('method_code', $request->method_code);
            }

            if ($request->has('user_id') && $request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->has('min_amount') && $request->min_amount) {
                $query->where('amount', '>=', $request->min_amount);
            }

            if ($request->has('max_amount') && $request->max_amount) {
                $query->where('amount', '<=', $request->max_amount);
            }

            // Pagination
            $limit = min($request->get('limit', 100), 500); // Max 500 per page
            $page = max($request->get('page', 1), 1);
            
            $deposits = $query->orderBy('id', 'desc')->paginate($limit, ['*'], 'page', $page);

            // Format response
            $data = $deposits->map(function ($deposit) {
                return [
                    'id' => $deposit->id,
                    'user_id' => $deposit->user_id,
                    'username' => $deposit->user->username ?? null,
                    'amount' => (float) $deposit->amount,
                    'charge' => (float) $deposit->charge,
                    'final_amo' => (float) $deposit->final_amo,
                    'trx' => $deposit->trx,
                    'status' => $deposit->status,
                    'method_code' => $deposit->method_code,
                    'method_name' => $deposit->gateway->name ?? null,
                    'created_at' => $deposit->created_at->toDateTimeString(),
                    'detail' => $deposit->detail,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'deposits' => $data,
                    'pagination' => [
                        'current_page' => $deposits->currentPage(),
                        'per_page' => $deposits->perPage(),
                        'total' => $deposits->total(),
                        'total_pages' => $deposits->lastPage(),
                    ],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving analytics data'
            ], 500);
        }
    }

    /**
     * Show single analytics record (Deposit)
     */
    public function showAnalytics($id)
    {
        try {
            $deposit = Deposit::with(['user', 'gateway'])->findOrFail($id);

            $data = [
                'id' => $deposit->id,
                'user_id' => $deposit->user_id,
                'username' => $deposit->user->username ?? null,
                'amount' => (float) $deposit->amount,
                'charge' => (float) $deposit->charge,
                'final_amo' => (float) $deposit->final_amo,
                'trx' => $deposit->trx,
                'status' => $deposit->status,
                'method_code' => $deposit->method_code,
                'method_name' => $deposit->gateway->name ?? null,
                'created_at' => $deposit->created_at->toDateTimeString(),
                'updated_at' => $deposit->updated_at->toDateTimeString(),
                'detail' => $deposit->detail,
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Analytics record not found'
            ], 404);
        }
    }

    /**
     * Batch cleanup (Bulk delete deposits)
     */
    public function batchCleanup(Request $request)
    {
        try {
            // Validate request
            if (!$request->has('confirm') || $request->confirm !== true) {
                return response()->json([
                    'success' => false,
                    'message' => 'Confirmation required. Set confirm to true.'
                ], 400);
            }

            $deletedIds = [];
            $deletedCount = 0;

            // Option 1: Delete by IDs
            if ($request->has('deposit_ids') && is_array($request->deposit_ids)) {
                $ids = array_slice($request->deposit_ids, 0, 100); // Max 100
                
                if (empty($ids)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No deposit IDs provided'
                    ], 400);
                }

                // Delete deposits
                $deposits = Deposit::whereIn('id', $ids)->get();
                $deletedCount = Deposit::whereIn('id', $ids)->delete();
                $deletedIds = $ids;

            } 
            // Option 2: Delete by filters
            elseif ($request->has('filters')) {
                $filters = $request->filters;
                $limit = min($request->get('limit', 100), 100); // Max 100 per batch
                
                $query = Deposit::query();

                if (isset($filters['status'])) {
                    $query->where('status', $filters['status']);
                }

                if (isset($filters['method_code'])) {
                    $query->where('method_code', $filters['method_code']);
                }

                if (isset($filters['date_from'])) {
                    $query->whereDate('created_at', '>=', $filters['date_from']);
                }

                if (isset($filters['date_to'])) {
                    $query->whereDate('created_at', '<=', $filters['date_to']);
                }

                if (isset($filters['user_id'])) {
                    $query->where('user_id', $filters['user_id']);
                }

                // Get IDs to delete (limit to 100)
                $deposits = $query->limit($limit)->get();
                $deletedIds = $deposits->pluck('id')->toArray();
                
                if (empty($deletedIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No deposits found matching filters'
                    ], 404);
                }

                // Delete deposits
                $deletedCount = Deposit::whereIn('id', $deletedIds)->delete();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Either deposit_ids or filters must be provided'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} deposits deleted successfully",
                'deleted_count' => $deletedCount,
                'deleted_ids' => $deletedIds,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during batch cleanup'
            ], 500);
        }
    }
}

