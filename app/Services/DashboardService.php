<?php

// ==========================================
// app/Services/DashboardService.php
// ==========================================

namespace App\Services;

use App\Models\Arrivals;
use App\Models\Items;
use App\Models\Product;

class DashboardService
{
    protected $productService;
    protected $arrivalsService;
    protected $itemsService;

    public function __construct(
        ProductService $productService,
        ArrivalsService $arrivalsService,
        ItemsService $itemsService
    ) {
        $this->productService = $productService;
        $this->arrivalsService = $arrivalsService;
        $this->itemsService = $itemsService;
    }

    public function getDashboardMetrics()
    {
        $productStats = $this->productService->getStatistics();
        $arrivalStats = $this->arrivalsService->getStatistics();
        $itemStats = $this->itemsService->getStatistics();

        return [
            'products' => $productStats,
            'arrivals' => $arrivalStats,
            'items' => $itemStats,
            'key_metrics' => [
                'total_revenue' => $arrivalStats['total_spent'],
                'total_profit' => $itemStats['total_profit'],
                'inventory_value' => $productStats['total_value'],
                'roi' => $arrivalStats['total_spent'] > 0 ?
                    ($itemStats['total_profit'] / $arrivalStats['total_spent']) * 100 : 0
            ]
        ];
    }

    public function getRecentArrivals($limit = 10)
    {
        return Arrivals::with(['country', 'item'])
            ->orderBy('arrival_date', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getRecentItems($limit = 10)
    {
        return Items::with(['product', 'arrival'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getInventoryHealth()
    {
        return [
            'total_products' => Product::count(),
            'low_stock_products' => Product::where('quantity', '<', 20)->count(),
            'out_of_stock' => Product::where('quantity', 0)->count(),
            'health_percentage' => Product::count() > 0 ?
                ((Product::where('quantity', '>', 20)->count() / Product::count()) * 100) : 0
        ];
    }

    public function getSalesAnalysis($days = 30)
    {
        $startDate = now()->subDays($days);

        return [
            'period_days' => $days,
            'total_items_sold' => Items::where('created_at', '>=', $startDate)->count(),
            'total_revenue' => Items::where('created_at', '>=', $startDate)->sum('price'),
            'total_cost' => Items::where('created_at', '>=', $startDate)->sum('cost'),
            'average_item_price' => Items::where('created_at', '>=', $startDate)->avg('price'),
            'profit' => Items::where('created_at', '>=', $startDate)->sum('price') -
                        Items::where('created_at', '>=', $startDate)->sum('cost')
        ];
    }

    public function getTopProducts($limit = 5)
    {
        return Product::withCount('items')
            ->orderBy('items_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTopSuppliers($limit = 5)
    {
        return Arrivals::selectRaw('supplier, count(*) as arrivals_count, SUM(totalSpend) as total_spent')
            ->groupBy('supplier')
            ->orderBy('total_spent', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTopCountries($limit = 5)
    {
        return Arrivals::selectRaw('countries.name, countries.code, count(arrivals.id) as arrivals_count, SUM(arrivals.totalSpend) as total_spent')
            ->join('countries', 'arrivals.country_id', '=', 'countries.id')
            ->groupBy('countries.id', 'countries.name', 'countries.code')
            ->orderBy('total_spent', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getConditionDistribution()
    {
        return Items::selectRaw('condition, count(*) as count, SUM(price) as total_value')
            ->groupBy('condition')
            ->pluck('total_value', 'condition');
    }

    public function getBrandAnalysis()
    {
        return Items::selectRaw('brand, count(*) as count, AVG(price) as avg_price, SUM(price) as total_value')
            ->groupBy('brand')
            ->orderBy('count', 'desc')
            ->get();
    }
}
