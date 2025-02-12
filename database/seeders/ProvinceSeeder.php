<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use Illuminate\Support\Facades\Storage;

class ProvinceSeeder extends Seeder
{
    public function run()
    {
        // Baca file CSV
        $filePath = storage_path('app/csv/provinsi.csv'); // Sesuaikan path file CSV
        $rows = array_map('str_getcsv', file($filePath));

        // Hapus header
        $header = array_shift($rows);

        // Masukkan data ke tabel
        foreach ($rows as $row) {
            Province::create([
                'code' => $row[0],
                'name' => $row[1],
            ]);
        }
    }
}
