<?php

namespace Database\Seeders;

use App\Models\Daira;
use App\Models\Commune;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('data\algeria_cities.json');
        $data = json_decode(file_get_contents($path), true);

        foreach ($data as $item) {
            $daira = Daira::where('name', $item['daira_name'])->first();

            if ($daira) {
                Commune::updateOrCreate([
                    'name' => $item['commune_name'],
                    'daira_id' => $daira->id,
                ], [
                    'name_ascii' => $item['commune_name_ascii'],
                ]);
            }
        }
    }
}
