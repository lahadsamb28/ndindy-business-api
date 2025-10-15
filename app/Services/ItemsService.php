<?php
namespace App\Services;

use App\Models\Items;
use App\Models\Product;
use Illuminate\Support\Collection;

class ItemsService
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function createItem(array $data)
    {
        $item = Items::create($data);

        // Mettre à jour la quantité du produit
        $product = Product::findOrFail($data['product_id']);
        $this->productService->incrementStock($product, 1, $item->price);

        return $item;
    }

    public function bulkCreateItems(array $itemsData)
    {
        $items = [];

        foreach ($itemsData as $data) {
            $item = Items::create($data);
           $product = Product::findOrFail($data['product_id']);
            $this->productService->incrementStock($product, 1, $item->price);
            $items[] = $item;
        }

        return $items;
    }

    public function getStatistics()
    {
        return [
            'total_items' => Items::count(),
            'total_cost' => Items::sum('cost'),
            'total_price' => Items::sum('price'),
            'average_cost' => Items::avg('cost'),
            'average_price' => Items::avg('price'),
            'total_profit' => Items::sum('price') - Items::sum('cost'),
            'profit_margin' => (Items::sum('price') - Items::sum('cost')) / Items::sum('price') * 100,
            'items_by_condition' => Items::selectRaw('condition, count(*) as count')
                ->groupBy('condition')
                ->pluck('count', 'condition')
        ];
    }

    public function getItemsByArrival($arrivalId): Collection
    {
        return Items::where('arrivals_id', $arrivalId)
            ->with(['product', 'arrival'])
            ->get();
    }

    public function getItemsByProduct($productId): Collection
    {
        return Items::where('product_id', $productId)
            ->with(['arrival', 'product'])
            ->paginate(15);
    }

    public function getItemsByCondition($condition): Collection
    {
        return Items::where('condition', $condition)
            ->with(['product', 'arrival'])
            ->get();
    }

    public function getItemsByBrand($brand): Collection
    {
        return Items::where('brand', 'like', "%{$brand}%")
            ->with(['product', 'arrival'])
            ->get();
    }

    public function getHighValueItems($minPrice = 500)
    {
        return Items::where('price', '>=', $minPrice)
            ->with(['product', 'arrival'])
            ->orderBy('price', 'desc')
            ->get();
    }

    public function getLowBatteryItems($batteryThreshold = 80)
    {
        return Items::whereNotNull('battery_health')
            ->where('battery_health', '<=', $batteryThreshold)
            ->with(['product', 'arrival'])
            ->orderBy('battery_health', 'asc')
            ->get();
    }

    public function calculateItemProfit(Items $item)
    {
        return [
            'cost' => $item->cost,
            'price' => $item->price,
            'profit' => $item->price - $item->cost,
            'margin_percent' => (($item->price - $item->cost) / $item->price) * 100
        ];
    }

    public function searchItems($searchTerm)
    {
        return Items::where('imei', 'like', "%{$searchTerm}%")
            ->orWhere('brand', 'like', "%{$searchTerm}%")
            ->orWhere('model', 'like', "%{$searchTerm}%")
            ->with(['product', 'arrival'])
            ->get();
    }
}
