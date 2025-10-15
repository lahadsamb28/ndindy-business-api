<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    // GET /api/dashboard/brand-analysis - Analyse par marque
    public function brandAnalysis()
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getBrandAnalysis()
        ]);
    }

    public function metrics()
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getDashboardMetrics(),
            'timestamp' => now()
        ]);
    }

     // GET /api/dashboard/recent-arrivals - Arrivages récents
    public function recentArrivals()
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getRecentArrivals(10)
        ]);
    }

    // GET /api/dashboard/recent-items - Items récents
    public function recentItems()
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getRecentItems(10)
        ]);
    }

    // GET /api/dashboard/inventory-health - Santé du stock
    public function inventoryHealth()
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getInventoryHealth()
        ]);
    }

    // GET /api/dashboard/sales-analysis - Analyse des ventes
    public function salesAnalysis()
    {
        $days = request('days', 30);

        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getSalesAnalysis($days)
        ]);
    }

    // GET /api/dashboard/top-products - Top produits
    public function topProducts()
    {
        $limit = request('limit', 5);

        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getTopProducts($limit)
        ]);
    }

    // GET /api/dashboard/top-suppliers - Top fournisseurs
    public function topSuppliers()
    {
        $limit = request('limit', 5);

        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getTopSuppliers($limit)
        ]);
    }

    // GET /api/dashboard/top-countries - Top pays
    public function topCountries()
    {
        $limit = request('limit', 5);

        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getTopCountries($limit)
        ]);
    }

    // GET /api/dashboard/condition-distribution - Distribution par condition
    public function conditionDistribution()
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getConditionDistribution()
        ]);
    }
}
