<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Téléphones', 'description' => 'Téléphones mobiles et smartphones'],
            ['name' => 'Accessoires', 'description' => 'Accessoires pour appareils mobiles'],
            ['name' => 'Informatique', 'description' => 'Ordinateurs et accessoires informatiques'],
            ['name' => 'Tablettes', 'description' => 'Tablettes et iPad'],
            ['name' => 'Montres connectées', 'description' => 'Smartwatches et montres connectées'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
