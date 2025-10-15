<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Items;
use App\Models\Arrivals;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrival1 = Arrivals::find(1);
        $arrival2 = Arrivals::find(2);
        $arrival3 = Arrivals::find(3);

        $items = [
            // Arrivage 1 - iPhone 15 Pro Max
            [
                'arrivals_id' => $arrival1->id,
                'product_id' => 1,
                'cost' => 950.00,
                'price' => 1299.00,
                'brand' => 'Apple',
                'model' => 'iPhone 15 Pro Max',
                'condition' => 'new',
                'imei' => '356938035643809',
                'battery_health' => 100,
            ],
            [
                'arrivals_id' => $arrival1->id,
                'product_id' => 1,
                'cost' => 920.00,
                'price' => 1199.00,
                'brand' => 'Apple',
                'model' => 'iPhone 15 Pro Max',
                'condition' => 'used',
                'imei' => '356938035643810',
                'battery_health' => 98,
            ],
            [
                'arrivals_id' => $arrival1->id,
                'product_id' => 3,
                'cost' => 750.00,
                'price' => 999.00,
                'brand' => 'Apple',
                'model' => 'iPhone 14 Pro',
                'condition' => 'used',
                'imei' => '356938035643811',
                'battery_health' => 95,
            ],

            // Arrivage 2 - Samsung
            [
                'arrivals_id' => $arrival2->id,
                'product_id' => 2,
                'cost' => 850.00,
                'price' => 1199.00,
                'brand' => 'Samsung',
                'model' => 'Galaxy S24 Ultra',
                'condition' => 'new',
                'imei' => '356938035643812',
                'battery_health' => 100,
            ],
            [
                'arrivals_id' => $arrival2->id,
                'product_id' => 2,
                'cost' => 820.00,
                'price' => 1099.00,
                'brand' => 'Samsung',
                'model' => 'Galaxy S24 Ultra',
                'condition' => 'used',
                'imei' => '356938035643813',
                'battery_health' => 98,
            ],
            [
                'arrivals_id' => $arrival2->id,
                'product_id' => 4,
                'cost' => 320.00,
                'price' => 449.00,
                'brand' => 'Samsung',
                'model' => 'Galaxy A54',
                'condition' => 'new',
                'imei' => '356938035643814',
                'battery_health' => 100,
            ],
            [
                'arrivals_id' => $arrival2->id,
                'product_id' => 5,
                'cost' => 650.00,
                'price' => 899.00,
                'brand' => 'Xiaomi',
                'model' => 'Mi 14 Pro',
                'condition' => 'new',
                'imei' => '356938035643815',
                'battery_health' => 100,
            ],

            // Arrivage 3 - Accessoires et autres
            [
                'arrivals_id' => $arrival3->id,
                'product_id' => 6,
                'cost' => 180.00,
                'price' => 249.00,
                'brand' => 'Apple',
                'model' => 'AirPods Pro 2',
                'condition' => 'new',
            ],
            [
                'arrivals_id' => $arrival3->id,
                'product_id' => 6,
                'cost' => 165.00,
                'price' => 229.00,
                'brand' => 'Apple',
                'model' => 'AirPods Pro 2',
                'condition' => 'used',
            ],
            [
                'arrivals_id' => $arrival3->id,
                'product_id' => 7,
                'cost' => 20.00,
                'price' => 35.00,
                'brand' => 'Anker',
                'model' => 'USB-C 65W',
                'condition' => 'new',
            ],
            [
                'arrivals_id' => $arrival3->id,
                'product_id' => 11,
                'cost' => 750.00,
                'price' => 1099.00,
                'brand' => 'Apple',
                'model' => 'iPad Pro 12.9"',
                'condition' => 'new',
                'imei' => '356938035643820',
                'battery_health' => 100,
            ],
            [
                'arrivals_id' => $arrival3->id,
                'product_id' => 13,
                'cost' => 310.00,
                'price' => 429.00,
                'brand' => 'Apple',
                'model' => 'Watch Series 9',
                'condition' => 'new',
            ],
        ];

        foreach ($items as $item) {
            Items::create($item);
        }
    }
}
