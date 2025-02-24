<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Subdistrict;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
            // Query database untuk mendapatkan kecamatan berdasarkan city_code
        


class WilayahController extends Controller
{   /**
     * Mendapatkan semua provinsi dengan pagination, limit, dan pencarian.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvinces(Request $request)
    {
        try {
            $limit = $request->query('limit', 20); // Default limit 20
            $query = Province::query();

            // Filter berdasarkan kode provinsi
            if ($request->has('code')) {
                return response()->json($query->where('code', $request->query('code'))->first());
            }

            // Pencarian berdasarkan keyword
            if ($request->has('keyword')) {
                $keyword = $request->query('keyword');
                $query->where('name', 'LIKE', "%{$keyword}%");
            }

            $provinces = $query->paginate($limit);
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

    public function provinces()
    {
        $path = storage_path('app/csv/provinsi.csv');
        $provinces = $this->readCsv($path);
        return response()->json($provinces);
    }

    public function regencies($province_id)
    {
        $path = storage_path('app/csv/kabupaten_kota.csv');
        $regencies = $this->readCsv($path);
        $filtered = array_filter($regencies, function($regency) use ($province_id) {
            return $regency['province_id'] == $province_id;
        });
        return response()->json(array_values($filtered));
    }

    public function districts($regency_id)
    {
        $path = storage_path('app/csv/kecamatan.csv');
        $districts = $this->readCsv($path);
        $filtered = array_filter($districts, function($district) use ($regency_id) {
            return $district['regency_id'] == $regency_id;
        });
        return response()->json(array_values($filtered));
    }

    public function villages($district_id)
    {
        $path = storage_path('app/csv/kelurahan.csv');
        $villages = $this->readCsv($path);
        $filtered = array_filter($villages, function($village) use ($district_id) {
            return $village['district_id'] == $district_id;
        });
        return response()->json(array_values($filtered));
    }

    private function readCsv($path)
    {
        if (!File::exists($path)) {
            return [];
        }

        $data = array_map('str_getcsv', file($path));
        $headers = array_shift($data);
        $array = [];
        
        foreach ($data as $row) {
            $array[] = array_combine($headers, $row);
        }

        return $array;
    }
}