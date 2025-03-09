<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

class VillageController extends Controller
{
    public function getByDistrict(Request $request, $district_id)
    {
        try {
            Log::info('Getting villages for district: ' . $district_id);
            $query = Village::where('district_id', $district_id)
                ->where('is_active', true);
            
            // Search by name if query parameter exists and length >= 3
            if ($request->has('q') && strlen($request->q) >= 3) {
                Log::info('Searching villages with keyword: ' . $request->q);
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
            $villages = $query->select('village_id as id', 'name')
                             ->skip(($page - 1) * $limit)
                             ->take($limit)
                             ->get();
            
            Log::info('Found ' . $villages->count() . ' villages');
            
            return response()->json([
                'data' => $villages,
                'meta' => [
                    'page' => (int)$page,
                    'limit' => (int)$limit,
                    'total' => $total,
                    'total_pages' => ceil($total / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting villages: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function show($id)
    {
        try {
            // Hapus titik dari id jika ada
            $id = str_replace('.', '', $id);
            
            Log::info('Getting village detail for ID: ' . $id);
            
            $village = Village::where('village_id', $id)
                ->where('is_active', true)
                ->firstOrFail();
            
            Log::info('Found village: ' . $village->name);
            
            return response()->json([
                'data' => [
                    'id' => $village->village_id,
                    'name' => $village->name,
                    'district_id' => $village->district_id,
                    'regency_id' => $village->regency_id,
                    'province_id' => $village->province_id
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting village detail: ' . $e->getMessage());
            return response()->json(['error' => 'Village not found'], 404);
        }
    }
} 