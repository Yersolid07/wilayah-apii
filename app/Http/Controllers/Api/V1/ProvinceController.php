<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        try {
            Log::info('Getting provinces list');
            $query = Province::where('is_active', true);
            
            // Search by name if query parameter exists and length >= 3
            if ($request->has('q') && strlen($request->q) >= 3) {
                Log::info('Searching provinces with keyword: ' . $request->q);
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
            $provinces = $query->select('province_id as id', 'name')
                             ->skip(($page - 1) * $limit)
                             ->take($limit)
                             ->get();
            
            Log::info('Found ' . $provinces->count() . ' provinces');
            
            return response()->json([
                'data' => $provinces,
                'meta' => [
                    'page' => (int)$page,
                    'limit' => (int)$limit,
                    'total' => $total,
                    'total_pages' => ceil($total / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting provinces: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function show($id)
    {
        try {
            $province = Province::where('province_id', $id)
                ->where('is_active', true)
                ->firstOrFail();
            
            return response()->json([
                'data' => [
                    'id' => $province->province_id,
                    'name' => $province->name
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting province detail: ' . $e->getMessage());
            return response()->json(['error' => 'Province not found'], 404);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required|string|unique:provinces_ref,province_id',
                'name' => 'required|string|max:255'
            ]);

            $province = Province::create([
                'province_id' => $request->id,
                'name' => $request->name,
                'is_active' => true,
                'created_at' => now(),
                'created_by' => 'system'
            ]);

            DB::commit();
            return response()->json([
                'data' => [
                    'id' => $province->province_id,
                    'name' => $province->name
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating province: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create province'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $province = Province::where('province_id', $id)
                ->where('is_active', true)
                ->firstOrFail();
            
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $province->update([
                'name' => $request->name,
                'updated_at' => now(),
                'updated_by' => 'system'
            ]);

            DB::commit();
            return response()->json([
                'data' => [
                    'id' => $province->province_id,
                    'name' => $province->name
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating province: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update province'], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $province = Province::where('province_id', $id)
                ->where('is_active', true)
                ->firstOrFail();
            
            $province->update([
                'is_active' => false,
                'updated_at' => now(),
                'updated_by' => 'system'
            ]);

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting province: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete province'], 500);
        }
    }
} 