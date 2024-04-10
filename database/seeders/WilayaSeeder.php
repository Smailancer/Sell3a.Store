<?php

namespace Database\Seeders;

use App\Models\Wilaya;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WilayaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $path = database_path('data/algeria_cities.json');
        $data = json_decode(file_get_contents($path), true);

        $wilayas = collect($data)->unique('wilaya_code');

        foreach ($wilayas as $item) {
            Wilaya::updateOrCreate([
                'code' => $item['wilaya_code'],
            ], [
                'name' => $item['wilaya_name'],
                'name_ascii' => $item['wilaya_name_ascii'],
            ]);
        }

    }
}
