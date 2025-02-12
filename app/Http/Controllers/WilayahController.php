<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Subdistrict;
use Illuminate\Support\Facades\Validator;
            // Query database untuk mendapatkan kecamatan berdasarkan city_code
        


class WilayahController extends Controller
{   /**
     * Mendapatkan semua provinsi.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvinces()
{
    try {
        $provinces = Province::all(); // Ambil semua data provinsi
        return response()->json($provinces);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}

public function getCitiesByProvince(Request $request)
{
    $validator = Validator::make($request->all(), [
        'province_code' => 'required|string|exists:provinces_code,code',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $provinceCode = $request->query('province_code');

    try {
        $cities = City::where('province_code', $provinceCode)->get(); // Ambil semua data kota
        return response()->json($cities);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}

public function getDistrictsByCity(Request $request)
{
    $validator = Validator::make($request->all(), [
        'province_code' => 'required|string|exists:provinces_code,code',
        'city_code' => 'required|string|exists:cities_code,code',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $cityCode = $request->query('city_code');

    try {
        $districts = District::where('city_code', $cityCode)->get(); // Ambil semua data kecamatan
        return response()->json($districts);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}

public function getSubdistrictsByDistrict(Request $request)
{
    $validator = Validator::make($request->all(), [
        'province_code' => 'required|string|exists:provinces_code,code',
        'city_code' => 'required|string|exists:cities_code,code',
        'district_code' => 'required|string|exists:districts_code,code',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $districtCode = $request->query('district_code');

    try {
        $subdistricts = Subdistrict::where('district_code', $districtCode)->get(); // Ambil semua data kelurahan
        return response()->json($subdistricts);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}
}