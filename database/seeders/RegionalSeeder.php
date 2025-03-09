<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegionalSeeder extends Seeder
{
    public function run()
    {
        try {
            // Import Provinces
            $provinces = $this->readCsv(storage_path('app/csv/provinsi.csv'));
            foreach ($provinces as $province) {
                $provinceId = $this->cleanId($province['id']);
                Log::info("Inserting province: {$provinceId} | Original: {$province['id']}");
                DB::table('provinces_ref')->insert([
                    'province_id' => $provinceId,
                    'name' => $province['name'],
                    'is_active' => true,
                    'created_at' => now()
                ]);
            }

            // Import Regencies
            $regencies = $this->readCsv(storage_path('app/csv/kabupaten_kota.csv'));
            foreach ($regencies as $regency) {
                $regencyId = $this->cleanId($regency['id']);
                $provinceId = substr($regencyId, 0, 2);
                Log::info("Inserting regency: {$regencyId} | Original: {$regency['id']}");
                
                DB::table('regencies_ref')->insert([
                    'regency_id' => $regencyId,
                    'province_id' => $provinceId,
                    'name' => $regency['name'],
                    'is_active' => true,
                    'created_at' => now()
                ]);
            }

            // Import Districts
            $districts = $this->readCsv(storage_path('app/csv/kecamatan.csv'));
            foreach ($districts as $district) {
                $districtId = $this->cleanId($district['id']);
                $regencyId = substr($districtId, 0, 4);
                $provinceId = substr($districtId, 0, 2);
                Log::info("Inserting district: {$districtId} | Original: {$district['id']}");
                
                DB::table('districts_ref')->insert([
                    'district_id' => $districtId,
                    'regency_id' => $regencyId,
                    'province_id' => $provinceId,
                    'name' => $district['name'],
                    'is_active' => true,
                    'created_at' => now()
                ]);
            }

            // Import Villages
            $villages = $this->readCsv(storage_path('app/csv/kelurahan.csv'));
            foreach ($villages as $village) {
                $parts = explode('.', $village['id']);
                if (count($parts) === 4) {
                    $provinceId = $parts[0];
                    $regencyId = $parts[0] . $parts[1];
                    $districtId = $parts[0] . $parts[1] . $parts[2];
                    $villageId = $parts[0] . $parts[1] . $parts[2] . $parts[3];
                    
                    Log::info("Processing village:");
                    Log::info("Original ID: {$village['id']}");
                    Log::info("Parsed IDs: Province={$provinceId}, Regency={$regencyId}, District={$districtId}, Village={$villageId}");
                    
                    DB::table('villages_ref')->insert([
                        'village_id' => $villageId,
                        'district_id' => $districtId,
                        'regency_id' => $regencyId,
                        'province_id' => $provinceId,
                        'name' => $village['name'],
                        'is_active' => true,
                        'created_at' => now()
                    ]);
                    Log::info("Village inserted successfully: {$villageId}");
                } else {
                    Log::warning("Invalid village ID format: {$village['id']}");
                }
            }

        } catch (\Exception $e) {
            Log::error('Error in RegionalSeeder: ' . $e->getMessage());
            throw $e;
        }
    }

    private function cleanId($id)
    {
        return str_replace('.', '', $id);
    }

    private function readCsv($path)
    {
        if (!file_exists($path)) {
            Log::error("CSV file not found: {$path}");
            throw new \Exception("CSV file not found: {$path}");
        }

        $data = array_map('str_getcsv', file($path));
        $headers = array_shift($data);
        $array = [];
        
        foreach ($data as $row) {
            if (count($headers) === count($row)) {
                $array[] = array_combine($headers, $row);
            } else {
                Log::warning("Skipping invalid row in CSV: " . implode(',', $row));
            }
        }

        return $array;
    }
} 