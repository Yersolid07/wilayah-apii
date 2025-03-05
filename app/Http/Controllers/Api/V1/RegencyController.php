<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Regency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegencyController extends Controller
{
    public function index(Request $request)
    {
        try {
            Log::info('Getting regencies list');
            $query = Regency::where('is_active', true);
            
            // Search by name if query parameter exists and length >= 3
            if ($request->has('q') && strlen($request->q) >= 3) {
                Log::info('Searching regencies with keyword: ' . $request->q);
                $query->where('name', 'LIKE', '%' . $request->q . '%');
            } elseif ($request->has('q') && strlen($request->q) < 3) {
                return response()->json([
                    'error' => 'Search query must be at least 3 characters'
                ], 422);
            }
            
            // Pagination
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            
            $total = $query->count();
            $regencies = $query->skip(($page - 1) * $limit)
                             ->take($limit)
                             ->get();
            
            Log::info('Found ' . $regencies->count() . ' regencies');
            
            return response()->json([
                'data' => $regencies->map(function($regency) {
                    return [
                        'id' => $regency->regency_id,
                        'name' => $regency->name,
                        'province_id' => $regency->province_id
                    ];
                }),
                'meta' => [
                    'page' => (int)$page,
                    'limit' => (int)$limit,
                    'total' => $total,
                    'total_pages' => ceil($total / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting regencies: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function show($id)
    {
        try {
            $regency = Regency::where('regency_id', $id)
                ->where('is_active', true)
                ->firstOrFail();
            
            return response()->json([
                'data' => [
                    'id' => $regency->regency_id,
                    'name' => $regency->name,
                    'province_id' => $regency->province_id
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting regency detail: ' . $e->getMessage());
            return response()->json(['error' => 'Regency not found'], 404);
        }
    }

    public function getByProvince(Request $request, $province_id)
    {
        try {
            Log::info('Getting regencies for province: ' . $province_id);
            $query = Regency::where('province_id', $province_id)
                ->where('is_active', true);
            
            // Search by name if query parameter exists and length >= 3
            if ($request->has('q') && strlen($request->q) >= 3) {
                Log::info('Searching regencies with keyword: ' . $request->q);
                $query->where('name', 'LIKE', '%' . $request->q . '%');
            } elseif ($request->has('q') && strlen($request->q) < 3) {
                return response()->json([
                    'error' => 'Search query must be at least 3 characters'
                ], 422);
            }
            
            // Pagination
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            
            $total = $query->count();
            $regencies = $query->skip(($page - 1) * $limit)
                             ->take($limit)
                             ->get();
            
            Log::info('Found ' . $regencies->count() . ' regencies');
            
            return response()->json([
                'data' => $regencies->map(function($regency) {
                    return [
                        'id' => $regency->regency_id,
                        'name' => $regency->name
                    ];
                }),
                'meta' => [
                    'page' => (int)$page,
                    'limit' => (int)$limit,
                    'total' => $total,
                    'total_pages' => ceil($total / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting regencies: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string|unique:regencies_ref,regency_id',
                'province_id' => 'required|string|exists:provinces_ref,province_id',
                'name' => 'required|string|max:255'
            ]);

            $regency = Regency::create([
                'regency_id' => $request->id,
                'province_id' => $request->province_id,
                'name' => $request->name,
                'is_active' => true,
                'created_at' => now(),
                'created_by' => 'system'
            ]);

            return response()->json([
                'data' => [
                    'id' => $regency->regency_id,
                    'name' => $regency->name,
                    'province_id' => $regency->province_id
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating regency: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create regency'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $regency = Regency::where('regency_id', $id)->firstOrFail();
            
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $regency->update([
                'name' => $request->name,
                'updated_at' => now(),
                'updated_by' => 'system'
            ]);

            return response()->json([
                'data' => [
                    'id' => $regency->regency_id,
                    'name' => $regency->name,
                    'province_id' => $regency->province_id
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating regency: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update regency'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $regency = Regency::where('regency_id', $id)->firstOrFail();
            
            $regency->update([
                'is_active' => false,
                'updated_at' => now(),
                'updated_by' => 'system'
            ]);

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting regency: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete regency'], 500);
        }
    }
} 