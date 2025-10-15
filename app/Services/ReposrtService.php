<?php

// ==========================================
// app/Services/StockAuditService.php
// ==========================================

namespace App\Services;

use App\Models\Product;
use App\Models\Arrivals;
use App\Models\Items;

class ReportService
{
    public function generateInventoryReport()
    {
        return [
            'timestamp' => now(),
            'total_products' => Product::count(),
            'total_quantity' => Product::sum('quantity'),
            'total_value' => Product::sum('totalPrice'),
            'low_stock_items' => Product::where('quantity', '<', 20)->count(),
            'out_of_stock' => Product::where('quantity', 0)->count(),
            'products' => Product::with('category')->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'category' => $product->category->name,
                        'quantity' => $product->quantity,
                        'unit_price_moy' => $product->totalPrice / ($product->quantity > 0 ? $product->quantity : 1),
                        'total_value' => $product->totalPrice,
                        'status' => $product->quantity == 0 ? 'Out of Stock' :
                                   ($product->quantity < 20 ? 'Low Stock' : 'In Stock')
                    ];
                })
        ];
    }

    public function generateArrivalsReport($startDate, $endDate)
    {
        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'summary' => [
                'total_arrivals' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->count(),
                'total_spent' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->sum('totalSpend'),
                'total_items' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->sum('totalItems'),
                'total_purchase_cost' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->sum('purchaseCost'),
                'total_shipping_cost' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->sum('shippingCost')
            ],
            'arrivals' => Arrivals::with('country')
                ->whereBetween('arrival_date', [$startDate, $endDate])
                ->get()
                ->map(function ($arrival) {
                    return [
                        'id' => $arrival->id,
                        'supplier' => $arrival->supplier,
                        'country' => $arrival->country->name,
                        'arrival_date' => $arrival->arrival_date,
                        'status' => $arrival->status,
                        'total_items' => $arrival->totalItems,
                        'total_spent' => $arrival->totalSpend,
                        'shipping_cost' => $arrival->shippingCost
                    ];
                })
        ];
    }

    public function generateSalesReport($startDate, $endDate)
    {
        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'summary' => [
                'total_items_sold' => Items::whereBetween('created_at', [$startDate, $endDate])->count(),
                'total_revenue' => Items::whereBetween('created_at', [$startDate, $endDate])->sum('price'),
                'total_cost' => Items::whereBetween('created_at', [$startDate, $endDate])->sum('cost'),
                'total_profit' => Items::whereBetween('created_at', [$startDate, $endDate])->sum('price') -
                                 Items::whereBetween('created_at', [$startDate, $endDate])->sum('cost'),
                'average_item_price' => Items::whereBetween('created_at', [$startDate, $endDate])->avg('price')
            ],
            'items' => Items::with(['product', 'arrival'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product' => $item->product->name,
                        'brand' => $item->brand,
                        'model' => $item->model,
                        'cost' => $item->cost,
                        'price' => $item->price,
                        'profit' => $item->price - $item->cost,
                        'condition' => $item->condition
                    ];
                })
        ];
    }

    public function generateProfitabilityReport()
    {
        $items = Items::all();
        $totalRevenue = $items->sum('price');
        $totalCost = $items->sum('cost');
        $totalProfit = $totalRevenue - $totalCost;

        return [
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'total_profit' => $totalProfit,
            'profit_margin' => $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0,
            'by_brand' => Items::selectRaw('brand, SUM(price) as revenue, SUM(cost) as cost')
                ->groupBy('brand')
                ->get()
                ->map(function ($item) {
                    return [
                        'brand' => $item->brand,
                        'revenue' => $item->revenue,
                        'cost' => $item->cost,
                        'profit' => $item->revenue - $item->cost,
                        'margin' => ($item->revenue - $item->cost) / $item->revenue * 100
                    ];
                }),
            'by_condition' => Items::selectRaw('condition, SUM(price) as revenue, SUM(cost) as cost')
                ->groupBy('condition')
                ->get()
                ->map(function ($item) {
                    return [
                        'condition' => $item->condition,
                        'revenue' => $item->revenue,
                        'cost' => $item->cost,
                        'profit' => $item->revenue - $item->cost,
                        'margin' => ($item->revenue - $item->cost) / $item->revenue * 100
                    ];
                })
        ];
    }
}
