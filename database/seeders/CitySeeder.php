<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use Illuminate\Support\Facades\Storage;

class CitySeeder extends Seeder
{
    public function run()
{
    \App\Models\City::truncate();

    $filePath = storage_path('app/csv/kabupaten_kota.csv');
    $rows = array_map('str_getcsv', file($filePath));
    $header = array_shift($rows);

    foreach ($rows as $row) {
        if (count($row) === 2) { // Pastikan ada 2 kolom (code dan name)
            $code = $row[0];
            $name = $row[1];

            // Ambil province_code dari 2 digit pertama code
            $province_code = substr($code, 0, 2);

            City::create([
                'code' => $code,
                'province_code' => $province_code,
                'name' => $name,
            ]);
        } else {
            echo "Invalid row in cities.csv: " . implode(',', $row) . PHP_EOL;
        }
    }
}
}