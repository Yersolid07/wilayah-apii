<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subdistrict;
use Illuminate\Support\Facades\Storage;

class SubdistrictSeeder extends Seeder
{
    public function run()
{
    \App\Models\Subdistrict::truncate();

    $filePath = storage_path('app/csv/kelurahan.csv');
    $rows = array_map('str_getcsv', file($filePath));
    $header = array_shift($rows);

    foreach ($rows as $row) {
        if (count($row) === 2) { // Pastikan ada 2 kolom (code dan name)
            $code = $row[0];
            $name = $row[1];

            // Ambil district_code dari 8 digit pertama code
            $district_code = substr($code, 0, 8);

            Subdistrict::create([
                'code' => $code,
                'district_code' => $district_code,
                'name' => $name,
            ]);
        } else {
            echo "Invalid row in subdistricts.csv: " . implode(',', $row) . PHP_EOL;
        }
    }
}
}