<?php
// ==========================================
// app/Services/ProductService.php
// ==========================================

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductService
{
    public function getStatistics()
    {
        return [
            'total_products' => Product::count(),
            'total_value' => Product::sum('totalPrice'),
            'low_stock_count' => Product::whereHas('items', function ($q) {
                $q->where('quantity', '<', 20);
            })->count(),
            'categories_count' => Product::distinct('category_id')->count(),
            'average_price' => Product::avg('totalPrice'),
            'total_items_count' => Product::withSum('items', 'quantity')->get()->sum('items_sum_quantity')
        ];
    }

    public function getLowStockProducts($threshold = 20): Collection
    {
        return Product::where('quantity', '<', $threshold)
            ->with(['category', 'items'])
            ->orderBy('quantity', 'asc')
            ->get();
    }

    public function getProductsByCategory($categoryId)
    {
        return Product::where('category_id', $categoryId)
            ->with(['items'])
            ->paginate(15);
    }
    public function searchProducts($searchTerm)
    {
        return Product::where('name', 'like', "%{$searchTerm}%")
            ->orWhere('description', 'like', "%{$searchTerm}%")
            ->with(['category', 'items'])
            ->paginate(15);
    }
    public function incrementStock($product, $amount, $totalPrice)
    {
        $product->increment('quantity', $amount);
        $product->increment('totalPrice', $totalPrice);

        return $product;
    }

    public function decrementStock($productId, $amount, $totalPrice)
    {
        $product = Product::findOrFail($productId);

        if ($product->quantity < $amount) {
            throw new \Exception('Quantité insuffisante');
        }

        $product->decrement('quantity', $amount);
        $product->decrement('totalPrice', $totalPrice);

        return $product;
    }


    public function updateStock($productId, $quantity)
    {
        $product = Product::findOrFail($productId);
        $product->update(['quantity' => $quantity]);

        return $product;
    }

    public function getCategoryStats()
    {
        return Product::selectRaw('categories.name, count(*) as count, SUM(products.totalPrice * products.quantity) as value')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.name')
            ->get();
    }
}
