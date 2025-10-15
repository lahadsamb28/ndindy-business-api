<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Items;
use App\Models\Product;
use App\Services\ItemsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemsController extends Controller
{
    protected $itemsService;

    public function __construct(ItemsService $itemsService)
    {
        $this->itemsService = $itemsService;
    }

    // GET /api/items - Liste tous les items
    public function index(Request $request)
    {
        $query = Items::with(['arrival', 'product']);

        if ($request->arrivals_id) {
            $query->where('arrivals_id', $request->arrivals_id);
        }

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->condition) {
            $query->where('condition', $request->condition);
        }

        if ($request->brand) {
            $query->where('brand', 'like', "%{$request->brand}%");
        }

        $items = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    // POST /api/items - Créer un item
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'arrivals_id' => 'required|exists:arrivals,id',
            'product_id' => 'required|exists:products,id',
            'cost' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'condition' => 'nullable|string',
            'imei' => 'nullable|string|max:255|unique:items,imei',
            'battery_health' => 'nullable|integer|min:0|max:100',
            'image_url' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $item = $this->itemsService->createItem($request->all());

            return response()->json([
                'message' => 'Item créé avec succès',
                'data' => $item->load(['arrival', 'product'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // GET /api/items/{id} - Détail d'un item
    public function show(Items $item)
    {
        return response()->json([
            'success' => true,
            'data' => $item->load(['arrival', 'product'])
        ]);
    }

    // PUT /api/items/{id} - Modifier un item
    public function update(Request $request, Items $item)
    {
        $validator = Validator::make($request->all(), [
            'cost' => 'sometimes|numeric|min:0',
            'price' => 'sometimes|numeric|min:0',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'condition' => 'nullable|string',
            'imei' => 'nullable|string|max:255|unique:items,imei,' . $item->id,
            'battery_health' => 'nullable|integer|min:0|max:100',
            'image_url' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item->update($request->all());

        return response()->json([
            'message' => 'Item mis à jour',
            'data' => $item->load(['arrival', 'product'])
        ]);
    }

    // DELETE /api/items/{id} - Supprimer un item
    public function destroy(Items $item)
    {
        $item->delete();

        return response()->json([
            'message' => 'Item supprimé avec succès'
        ]);
    }

    // GET /api/items/by-arrival/{arrivalId} - Items par arrivage
    public function byArrival($arrivalId)
    {
        $items = $this->itemsService->getItemsByArrival($arrivalId);

        return response()->json([
            'success' => true,
            'count' => $items->count(),
            'data' => $items
        ]);
    }

    // GET /api/items/by-product/{productId} - Items par produit
    public function byProduct($productId)
    {
        $items = $this->itemsService->getItemsByProduct($productId);

        return response()->json([
            'success' => true,
            'count' => $items->count(),
            'data' => $items
        ]);
    }

    // GET /api/items/by-condition/{condition} - Items par condition
    public function byCondition($condition)
    {
        $items = $this->itemsService->getItemsByCondition($condition);

        return response()->json([
            'success' => true,
            'condition' => $condition,
            'count' => $items->count(),
            'data' => $items
        ]);
    }

    // POST /api/items/bulk-create - Créer plusieurs items
    public function bulkCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.arrivals_id' => 'required|exists:arrivals,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.cost' => 'required|numeric|min:0',
            'items.*.price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $items = $this->itemsService->bulkCreateItems($request->items);

            return response()->json([
                'message' => 'Items créés avec succès',
                'count' => count($items),
                'data' => $items
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // GET /api/items/stats - Stats des items
    public function stats()
    {
        return response()->json([
            'success' => true,
            'data' => $this->itemsService->getStatistics()
        ]);
    }
}

