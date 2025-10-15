<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Country;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Téléphones
            [
                'name' => 'iPhone 15 Pro Max',
                'description' => 'Dernier modèle iPhone avec puce A17 Pro',
                'category_id' => Category::where('name', 'Téléphones')->first()->id
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Flagship Samsung avec IA avancée',
                'category_id' => Category::where('name', 'Téléphones')->first()->id
            ],
            [
                'name' => 'iPhone 14 Pro',
                'description' => 'iPhone 14 Pro avec Dynamic Island',
                'category_id' => Category::where('name', 'Téléphones')->first()->id
            ],
            [
                'name' => 'Samsung Galaxy A54',
                'description' => 'Milieu de gamme Samsung performant',
                'category_id' => Category::where('name', 'Téléphones')->first()->id
            ],
            [
                'name' => 'Xiaomi 14 Pro',
                'description' => 'Flagship Xiaomi avec Snapdragon 8 Gen 3',
                'category_id' => Category::where('name', 'Téléphones')->first()->id
            ],

            // Accessoires
            [
                'name' => 'AirPods Pro 2',
                'description' => 'Écouteurs sans fil Apple avec ANC',
                'category_id' => Category::where('name', 'Accessoires')->first()->id
            ],
            [
                'name' => 'Chargeur USB-C 65W',
                'description' => 'Chargeur rapide universel',
                'category_id' => Category::where('name', 'Accessoires')->first()->id
            ],
            [
                'name' => 'Coque iPhone 15 Pro',
                'description' => 'Coque de protection premium',
                'category_id' => Category::where('name', 'Accessoires')->first()->id
            ],

            // Informatique
            [
                'name' => 'MacBook Air M2',
                'description' => 'Ordinateur portable Apple avec puce M2',
                'category_id' => Category::where('name', 'Informatique')->first()->id
            ],
            [
                'name' => 'Dell XPS 15',
                'description' => 'Ordinateur portable professionnel',
                'category_id' => Category::where('name', 'Informatique')->first()->id
            ],

            // Tablettes
            [
                'name' => 'iPad Pro 12.9"',
                'description' => 'Tablette professionnelle Apple',
                'category_id' => Category::where('name', 'Tablettes')->first()->id
            ],
            [
                'name' => 'Samsung Galaxy Tab S9',
                'description' => 'Tablette Android haut de gamme',
                'category_id' => Category::where('name', 'Tablettes')->first()->id
            ],

            // Montres
            [
                'name' => 'Apple Watch Series 9',
                'description' => 'Montre connectée Apple dernière génération',
                'category_id' => Category::where('name', 'Montres connectées')->first()->id
            ],
            [
                'name' => 'Samsung Galaxy Watch 6',
                'description' => 'Montre connectée Samsung',
                'category_id' => Category::where('name', 'Montres connectées')->first()->id
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
