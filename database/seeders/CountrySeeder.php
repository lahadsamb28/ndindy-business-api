<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
           ['name' => 'Sénégal', 'code' => 'SN'],
            ['name' => 'États-Unis', 'code' => 'US'],
            ['name' => 'Chine', 'code' => 'CN'],
            ['name' => 'Corée du Sud', 'code' => 'KR'],
            ['name' => 'Japon', 'code' => 'JP'],
            ['name' => 'Allemagne', 'code' => 'DE'],
            ['name' => 'France', 'code' => 'FR'],
            ['name' => 'Maroc', 'code' => 'MA'],
            ['name' => 'Taïwan', 'code' => 'TW'],
            ['name' => 'Singapour', 'code' => 'SG'],
            ['name' => 'Émirats Arabes Unis', 'code' => 'AE'],
            ['name' => 'Turquie', 'code' => 'TR'],
            ['name' => 'Canada', 'code' => 'CA'],
            ['name' => 'Royaume-Uni', 'code' => 'GB'],
            ['name' => 'Suisse', 'code' => 'CH'],
            ['name' => 'Brésil', 'code' => 'BR'],

        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
