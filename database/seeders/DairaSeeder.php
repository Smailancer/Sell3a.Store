<?php

namespace Database\Seeders;

use App\Models\Daira;
use App\Models\Wilaya;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DairaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('data\algeria_cities.json');
        $data = json_decode(file_get_contents($path), true);

        foreach ($data as $item) {
            $wilaya = Wilaya::where('code', $item['wilaya_code'])->first();

            if ($wilaya) {
                Daira::updateOrCreate([
                    'name' => $item['daira_name'],
                    'wilaya_id' => $wilaya->id,
                ], [
                    'name_ascii' => $item['daira_name_ascii'],
                ]);
            }
        }
    }
}
