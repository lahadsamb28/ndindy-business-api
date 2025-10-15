<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'items']);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->low_stock) {
            $query->where('quantity', '<', 20);
        }

        $products = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id'
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::create($request->all());

        return response()->json([
            'message' => 'Produit créé avec succès',
            'data' => $product->load(['category', 'items'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'data' => $product->load(['category', 'items', 'arrivals'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, Product $product)
    {
         $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'sometimes|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update($request->all());

        return response()->json([
            'message' => 'Produit mis à jour',
            'data' => $product->load(['category', 'items'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->items()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer le produit car il est associé à des articles.'
            ], 400);
        }
        $product->delete();

        return response()->json([
            'message' => 'Produit supprimé avec succès'
        ]);
    }

    public function lowStock(Request $request)
    {
        $threshold = $request->threshold ?? 20;
        $lowStockProducts = $this->productService->getLowStockProducts($threshold);

        return response()->json([
            'success' => true,
            'threshold' => $threshold,
            'count' => $lowStockProducts->count(),
            'data' => $lowStockProducts
        ]);
    }

     public function byCategory($categoryId)
    {
        $products = $this->productService->getProductsByCategory($categoryId);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    // GET /api/products/stats - Stats des produits
    public function stats()
    {
        return response()->json([
            'success' => true,
            'data' => $this->productService->getStatistics()
        ]);
    }
}
