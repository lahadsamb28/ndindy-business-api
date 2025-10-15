<?php
// ==========================================
// app/Services/ExportService.php
// ==========================================

namespace App\Services;

use App\Exports\GenericReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Arrivals;
use App\Models\Items;

class ExportService
{
    protected $now;

    public function __construct()
    {
        $this->now = Carbon::now()->format('F Y');
    }

    public function exportInventoryReport()
    {
        $summary =[
            'Date' => now()->format('Y-m-d H:i:s'),
            'Total products' => Product::count(),
            'Total quantity' => Product::sum('quantity'),
            'Total value' => Product::sum('totalPrice'),
            'Low stock items' => Product::where('quantity', '<', 20)->count(),
            'Out of stock' => Product::where('quantity', 0)->count(),
        ];

        $Details = Product::with('category')->get()
            ->map(function ($product) {
                return [
                    'ID' => $product->id,
                    'Name' => $product->name,
                    'Category' => $product->category->name,
                    'Quantity' => $product->quantity,
                    'Unit Price Avg' => $product->totalPrice / ($product->quantity > 0 ? $product->quantity : 1),
                    'Total Value' => $product->totalPrice,
                    'Status' => $product->quantity == 0 ? 'Out of Stock' :
                               ($product->quantity < 20 ? 'Low Stock' : 'In Stock')
                ];
            })->toArray();

        $chartData = [
            'In stock' => Product::where('quantity', '>=', 20)->count(),
            'Low stock' => Product::whereBetween('quantity', [1, 19])->count(),
            'Out of stock' => Product::where('quantity', 0)->count(),
        ];

        return Excel::download(new GenericReportExport($summary, $Details, $chartData, 'Inventory Report'), 'inventory_report_'.$this->now.'.xlsx');
    }

    public function exportArrivalsReport(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $summary = [
            'period' => [
                'From' => $startDate,
                'To' => $endDate
            ],
            'summary' => [
                'Total arrivals' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->count(),
                'Total spent' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->sum('totalSpend'),
                'Total items' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->sum('totalItems'),
                'Total purchase cost' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->sum('purchaseCost'),
                'Total shipping cost' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->sum('shippingCost')
            ]
        ];
        $details = Arrivals::with('country')
            ->whereBetween('arrival_date', [$startDate, $endDate])
            ->get()
            ->map(function ($arrival) {
                return [
                    'ID' => $arrival->id,
                    'Supplier' => $arrival->supplier,
                    'Country' => $arrival->country->name,
                    'Arrival Date' => $arrival->arrival_date,
                    'Status' => $arrival->status,
                    'Total Items' => $arrival->totalItems,
                    'Total Spent' => $arrival->totalSpend,
                    'Purchase Cost' => $arrival->purchaseCost,
                    'Shipping Cost' => $arrival->shippingCost
                ];
            })->toArray();
        $chartData = [
            'Pending' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->where('status', 'pending')->count(),
            'Received' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->where('status', 'received')->count(),
            'Cancelled' => Arrivals::whereBetween('arrival_date', [$startDate, $endDate])->where('status', 'cancelled')->count(),
        ];
        return Excel::download(new GenericReportExport($summary, $details, $chartData, 'Arrivals Report'), 'arrivals_report_'.$this->now.'.xlsx');
    }

    // public function exportSalesReport(Request $request)
    // {
    //     $startDate = $request->start_date;
    //     $endDate = $request->end_date;
    //     $summary = [
    //         'period' => [
    //             'From' => $startDate,
    //             'To' => $endDate
    //         ],
    //         'summary' => [
    //             'Total items sold' => Items::whereBetween('created_at', [$startDate, $endDate])->count(),
    //             'Total revenue' => Items::whereBetween('created_at', [$startDate, $endDate])->sum('price'),
    //             'Total cost' => Items::whereBetween('created_at', [$startDate, $endDate])->sum('cost'),
    //             'Total profit' => Items::whereBetween('created_at', [$startDate, $endDate])->sum('price') - Items::whereBetween('created_at', [$startDate, $endDate])->sum('cost'),
    //             'Average item price' => Items::whereBetween('created_at', [$startDate, $endDate])->avg('price')
    //         ]
    //     ];
    //     $details = Items::with('product', 'arrival')
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->get()
    //         ->map(function ($item) {
    //             return [
    //                 'ID' => $item->id,
    //                 'Product' => $item->product->name,
    //                 'Brand' => $item->brand,
    //                 'Model' => $item->model,
    //                 'Cost' => $item->cost,
    //                 'Price' => $item->price,
    //                 'Profit' => $item->price - $item->cost,
    //                 'Condition' => $item->condition,
    //             ];
    //         })->toArray();
    //     $chartData = [
    //         'Sold Items' => Items::whereBetween('created_at', [$startDate, $endDate])->count(),
    //         'Total Revenue' => Items::whereBetween('created_at', [$startDate, $endDate])->sum('price'),
    //         'Total Profit' => Items::whereBetween('created_at', [$startDate, $endDate])->sum('price') - Items::whereBetween('created_at', [$startDate, $endDate])->sum('cost'),
    //     ];
    //     return Excel::download(new GenericReportExport($summary, $details, $chartData, 'Sales Report'), 'sales_report_'.$this->now.'.xlsx');
    // }

    // public function exportToCsv($products)
    // {
    //     $csv = "Nom,SKU,Catégorie,Pays,Prix,Quantité,Valeur\n";

    //     foreach ($products as $p) {
    //         $total = $p['price'] * $p['quantity'];
    //         $csv .= "{$p['name']},{$p['sku']},{$p['category']},{$p['country']},{$p['price']},{$p['quantity']},{$total}\n";
    //     }

    //     return $csv;
    // }
}
