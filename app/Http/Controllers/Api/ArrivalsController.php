<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatusArrival;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ArrivalsService;
use App\Models\Arrivals;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class ArrivalsController extends Controller
{
    protected $arrivalsService;
    public function __construct(ArrivalsService $arrivalService)
    {
        $this->arrivalsService = $arrivalService;
    }

   // GET /api/arrivals - Liste tous les arrivages
    public function index(Request $request)
    {
        $query = Arrivals::with(['country', 'item']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->country_id) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->date_from) {
            $query->whereDate('arrival_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('arrival_date', '<=', $request->date_to);
        }

        $arrivals = $query->orderBy('arrival_date', 'desc')->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $arrivals
        ]);
    }

    // POST /api/arrivals - Créer un arrivage
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'totalSpend' => 'required|numeric|min:0',
            'purchaseCost' => 'required|numeric|min:0',
            'shippingCost' => 'required|numeric|min:0',
            'supplier' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'totalItems' => 'required|integer|min:1',
            'arrival_date' => 'required|date',
            'sku' => 'nullable|string|unique:arrivals,sku',
            'status' => 'nullable|string',
            'items' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $arrival = $this->arrivalsService->createArrival($request->all());

            return response()->json([
                'message' => 'Arrivage créé avec succès',
                'data' => $arrival->load(['country', 'item'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // GET /api/arrivals/{id} - Détail d'un arrivage
    public function show(Arrivals $arrival)
    {
        return response()->json([
            'success' => true,
            'data' => $arrival->load(['country', 'item', 'item.product'])
        ]);
    }

    // PUT /api/arrivals/{id} - Modifier un arrivage
    public function update(Request $request, Arrivals $arrival)
    {
        $validator = Validator::make($request->all(), [
            'totalSpend' => 'sometimes|numeric|min:0',
            'purchaseCost' => 'sometimes|numeric|min:0',
            'shippingCost' => 'sometimes|numeric|min:0',
            'supplier' => 'sometimes|string|max:255',
            'country_id' => 'sometimes|exists:countries,id',
            'totalItems' => 'sometimes|integer|min:1',
            'arrival_date' => 'sometimes|date',
            'sku' => 'sometimes|string|unique:arrivals,sku,' . $arrival->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $arrival->update($request->all());

        return response()->json([
            'message' => 'Arrivage mis à jour',
            'data' => $arrival->load(['country', 'item'])
        ]);
    }

    // DELETE /api/arrivals/{id} - Supprimer un arrivage
    public function destroy(Arrivals $arrival)
    {
        if ($arrival->item()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer un arrivage avec des items'
            ], 400);
        }

        $arrival->delete();

        return response()->json([
            'message' => 'Arrivage supprimé avec succès'
        ]);
    }

    // GET /api/arrivals/by-status/{status} - Arrivages par statut
    public function byStatus($status)
    {
        $arrivals = $this->arrivalsService->getArrivalsByStatus($status);

        return response()->json([
            'success' => true,
            'status' => $status,
            'count' => $arrivals->count(),
            'data' => $arrivals
        ]);
    }

    // GET /api/arrivals/by-country/{countryId} - Arrivages par pays
    public function byCountry($countryId)
    {
        $arrivals = $this->arrivalsService->getArrivalsByCountry($countryId);

        return response()->json([
            'success' => true,
            'data' => $arrivals
        ]);
    }

    // GET /api/arrivals/stats - Stats des arrivages
    public function stats()
    {
        return response()->json([
            'success' => true,
            'data' => $this->arrivalsService->getStatistics()
        ]);
    }

    // POST /api/arrivals/{id}/update-status - Changer le statut
    public function updateStatus(Request $request, Arrivals $arrival)
    {
        $request->validate([
            'status' => ['required', new Enum(StatusArrival::class)]
        ]);

        $arrival->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Statut mis à jour',
            'data' => $arrival
        ]);
    }
}
