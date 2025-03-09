<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

class DistrictController extends Controller
{
    public function getByRegency(Request $request, $regency_id)
    {
        try {
            Log::info('Getting districts for regency: ' . $regency_id);
            $query = District::where('regency_id', $regency_id)
                ->where('is_active', true);
            
            // Search by name if query parameter exists and length >= 3
            if ($request->has('q') && strlen($request->q) >= 3) {
                Log::info('Searching districts with keyword: ' . $request->q);
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
            $districts = $query->select('district_id as id', 'name')
                              ->skip(($page - 1) * $limit)
                              ->take($limit)
                              ->get();
            
            Log::info('Found ' . $districts->count() . ' districts');
            
            return response()->json([
                'data' => $districts,
                'meta' => [
                    'page' => (int)$page,
                    'limit' => (int)$limit,
                    'total' => $total,
                    'total_pages' => ceil($total / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting districts: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function show($id)
    {
        try {
            Log::info('Getting district detail for ID: ' . $id);
            $district = District::where('district_id', $id)
                ->where('is_active', true)
                ->firstOrFail();
            
            Log::info('Found district: ' . $district->name);
            
            return response()->json([
                'data' => [
                    'id' => $district->district_id,
                    'name' => $district->name,
                    'regency_id' => $district->regency_id,
                    'province_id' => $district->province_id
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting district detail: ' . $e->getMessage());
            return response()->json(['error' => 'District not found'], 404);
        }
    }
} 