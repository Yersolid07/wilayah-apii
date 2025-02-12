<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use Illuminate\Support\Facades\Storage;

class DistrictSeeder extends Seeder
{
    public function run()
{
    \App\Models\District::truncate();

    $filePath = storage_path('app/csv/kecamatan.csv');
    $rows = array_map('str_getcsv', file($filePath));
    $header = array_shift($rows);

    foreach ($rows as $row) {
        if (count($row) === 2) { // Pastikan ada 2 kolom (code dan name)
            $code = $row[0];
            $name = $row[1];

            // Ambil city_code dari 5 digit pertama code
            $city_code = substr($code, 0, 5);

            District::create([
                'code' => $code,
                'city_code' => $city_code,
                'name' => $name,
            ]);
        } else {
            echo "Invalid row in districts.csv: " . implode(',', $row) . PHP_EOL;
        }
    }
}
}