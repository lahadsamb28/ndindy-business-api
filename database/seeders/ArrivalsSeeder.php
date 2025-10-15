<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Arrivals;
use App\Models\Country;

class ArrivalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrivals = [
            [
                'totalSpend' => 50000.00,
                'purchaseCost' => 42000.00,
                'shippingCost' => 3000.00,
                'supplier' => 'TechGlobal USA',
                'country_id' => Country::where('code', 'US')->first()->id,
                'totalItems' => 50,
                'arrival_date' => now()->subDays(15),
                'sku' => 'IP20PM-001',
                'status' => 'completed'
            ],
            [
                'totalSpend' => 35000.00,
                'purchaseCost' => 28000.00,
                'shippingCost' => 2500.00,
                'supplier' => 'China Electronics',
                'country_id' => Country::where('code', 'CN')->first()->id,
                'totalItems' => 80,
                'arrival_date' => now()->subDays(10),
                'sku' => 'IP18PM-001',
                'status' => 'completed'
            ],
            [
                'totalSpend' => 45000.00,
                'purchaseCost' => 38000.00,
                'shippingCost' => 2800.00,
                'supplier' => 'Korea Tech',
                'country_id' => Country::where('code', 'KR')->first()->id,
                'totalItems' => 45,
                'arrival_date' => now()->subDays(5),
                'sku' => 'IP15PM-003',
                'status' => 'arrived'
            ],
            [
                'totalSpend' => 60000.00,
                'purchaseCost' => 52000.00,
                'shippingCost' => 3500.00,
                'supplier' => 'Dubai Mobile Trading',
                'country_id' => Country::where('code', 'AE')->first()->id,
                'totalItems' => 35,
                'arrival_date' => now()->addDays(7),
                'sku' => 'IP15PM-002',
                'status' => 'in_transit'
            ],
            [
                'totalSpend' => 28000.00,
                'purchaseCost' => 23000.00,
                'shippingCost' => 2000.00,
                'supplier' => 'TechGlobal USA',
                'country_id' => Country::where('code', 'US')->first()->id,
                'totalItems' => 30,
                'arrival_date' => now()->addDays(14),
                'sku' => 'IP15PM-001',
                'status' => 'pending'
            ],
        ];

        foreach ($arrivals as $arrival) {
            Arrivals::create($arrival);
        }

    }
}
