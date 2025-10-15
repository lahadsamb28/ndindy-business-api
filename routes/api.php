<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\ArrivalsController;
use App\Http\Controllers\Api\ItemsController;
use App\Http\Controllers\Api\DashboardController;

// ==========================================
// Routes publiques (pas d'authentification)
// ==========================================
Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
});

// ==========================================
// Routes protégées (authentification Sanctum)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // ========== AUTH ==========
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

     // ========== PRODUITS ==========
    Route::prefix('products')->group(function () {
        // CRUD
        Route::get('/', [ProductController::class, 'index']);                      // GET /api/products
        Route::post('/', [ProductController::class, 'store']);                     // POST /api/products
        Route::get('/{product}', [ProductController::class, 'show']);              // GET /api/products/{id}
        Route::put('/{product}', [ProductController::class, 'update']);            // PUT /api/products/{id}
        Route::delete('/{product}', [ProductController::class, 'destroy']);        // DELETE /api/products/{id}

        // Filtres et recherche
        Route::get('/low-stock', [ProductController::class, 'lowStock']);          // GET /api/products/low-stock
        Route::get('/by-category/{categoryId}', [ProductController::class, 'byCategory']); // GET /api/products/by-category/{id}
        Route::get('/stats', [ProductController::class, 'stats']);                 // GET /api/products/stats
    });

    // ========== CATÉGORIES ==========
    Route::apiResource('categories', CategoryController::class);                   // CRUD /api/categories

    // ========== PAYS ==========
    Route::apiResource('countries', CountryController::class);                     // CRUD /api/countries

    // ========== ARRIVAGES ==========
    Route::prefix('arrivals')->group(function () {
        // CRUD
        Route::get('/', [ArrivalsController::class, 'index']);                     // GET /api/arrivals
        Route::post('/', [ArrivalsController::class, 'store']);                    // POST /api/arrivals
        Route::get('/{arrival}', [ArrivalsController::class, 'show']);             // GET /api/arrivals/{id}
        Route::put('/{arrival}', [ArrivalsController::class, 'update']);           // PUT /api/arrivals/{id}
        Route::delete('/{arrival}', [ArrivalsController::class, 'destroy']);       // DELETE /api/arrivals/{id}

        // Filtres et actions
        Route::get('/by-status/{status}', [ArrivalsController::class, 'byStatus']); // GET /api/arrivals/by-status/{status}
        Route::get('/by-country/{countryId}', [ArrivalsController::class, 'byCountry']); // GET /api/arrivals/by-country/{id}
        Route::post('/{arrival}/update-status', [ArrivalsController::class, 'updateStatus']); // POST /api/arrivals/{id}/update-status
        Route::get('/stats', [ArrivalsController::class, 'stats']);                // GET /api/arrivals/stats
    });

    // ========== ITEMS ==========
    Route::prefix('items')->group(function () {
        // CRUD
        Route::get('/', [ItemsController::class, 'index']);                        // GET /api/items
        Route::post('/', [ItemsController::class, 'store']);                       // POST /api/items
        Route::get('/{item}', [ItemsController::class, 'show']);                   // GET /api/items/{id}
        Route::put('/{item}', [ItemsController::class, 'update']);                 // PUT /api/items/{id}
        Route::delete('/{item}', [ItemsController::class, 'destroy']);             // DELETE /api/items/{id}

        // Filtres et recherche
        Route::get('/by-arrival/{arrivalId}', [ItemsController::class, 'byArrival']); // GET /api/items/by-arrival/{id}
        Route::get('/by-product/{productId}', [ItemsController::class, 'byProduct']); // GET /api/items/by-product/{id}
        Route::get('/by-condition/{condition}', [ItemsController::class, 'byCondition']); // GET /api/items/by-condition/{condition}
        Route::post('/bulk-create', [ItemsController::class, 'bulkCreate']);       // POST /api/items/bulk-create
        Route::get('/stats', [ItemsController::class, 'stats']);                   // GET /api/items/stats
    });

    // ========== DASHBOARD ==========
    Route::prefix('dashboard')->group(function () {
        Route::get('/metrics', [DashboardController::class, 'metrics']);           // GET /api/dashboard/metrics
        Route::get('/recent-arrivals', [DashboardController::class, 'recentArrivals']); // GET /api/dashboard/recent-arrivals
        Route::get('/recent-items', [DashboardController::class, 'recentItems']);  // GET /api/dashboard/recent-items
        Route::get('/inventory-health', [DashboardController::class, 'inventoryHealth']); // GET /api/dashboard/inventory-health
        Route::get('/sales-analysis', [DashboardController::class, 'salesAnalysis']); // GET /api/dashboard/sales-analysis
        Route::get('/top-products', [DashboardController::class, 'topProducts']);  // GET /api/dashboard/top-products
        Route::get('/top-suppliers', [DashboardController::class, 'topSuppliers']); // GET /api/dashboard/top-suppliers
        Route::get('/top-countries', [DashboardController::class, 'topCountries']); // GET /api/dashboard/top-countries
        Route::get('/condition-distribution', [DashboardController::class, 'conditionDistribution']); // GET /api/dashboard/condition-distribution
        Route::get('/brand-analysis', [DashboardController::class, 'brandAnalysis']); // GET /api/dashboard/brand-analysis
    });

    // ========== RAPPORTS ==========
    Route::prefix('reports')->group(function () {
        Route::get('/inventory', [ReportController::class, 'inventory']);          // GET /api/reports/inventory
        Route::get('/arrivals', [ReportController::class, 'arrivals']);            // GET /api/reports/arrivals
        Route::get('/sales', [ReportController::class, 'sales']);                  // GET /api/reports/sales
        Route::get('/profitability', [ReportController::class, 'profitability']);  // GET /api/reports/profitability
    });

    //=========== EXPORTS ===========
    Route::prefix('exports')->group(function () {
        Route::get('/inventory', [ReportController::class, 'exportInventoryExcel']);          // GET /api/exports/inventory
        Route::get('/arrivals', [ReportController::class, 'exportArrivalsExcel']);            // GET /api/exports/arrivals
    });
});

// ========== Route de fallback ==========
Route::fallback(function () {
    return response()->json(['message' => 'Route non trouvée'], 404);
});

/**
* ==========================================
 * RÉSUMÉ COMPLET DES ENDPOINTS
 * ==========================================
 *
 * AUTH (2):
 * POST   /api/auth/register                      - Inscription
 * POST   /api/auth/login                         - Connexion
 * POST   /api/auth/logout                        - Déconnexion
 * GET    /api/auth/me                            - Profil utilisateur
 *
 * PRODUITS (6):
 * GET    /api/products                           - Liste tous les produits
 * POST   /api/products                           - Créer un produit
 * GET    /api/products/{id}                      - Détail d'un produit
 * PUT    /api/products/{id}                      - Modifier un produit
 * DELETE /api/products/{id}                      - Supprimer un produit
 * GET    /api/products/low-stock                 - Produits stock faible
 * GET    /api/products/by-category/{id}          - Produits par catégorie
 * GET    /api/products/stats                     - Stats des produits
 *
 * CATÉGORIES (5):
 * GET    /api/categories                         - Liste des catégories
 * POST   /api/categories                         - Créer une catégorie
 * GET    /api/categories/{id}                    - Détail catégorie
 * PUT    /api/categories/{id}                    - Modifier une catégorie
 * DELETE /api/categories/{id}                    - Supprimer une catégorie
 *
 * PAYS (5):
 * GET    /api/countries                          - Liste des pays
 * POST   /api/countries                          - Ajouter un pays
 * GET    /api/countries/{id}                     - Détail d'un pays
 * PUT    /api/countries/{id}                     - Modifier un pays
 * DELETE /api/countries/{id}                     - Supprimer un pays
 *
 * ARRIVAGES (8):
 * GET    /api/arrivals                           - Liste tous les arrivages
 * POST   /api/arrivals                           - Créer un arrivage
 * GET    /api/arrivals/{id}                      - Détail d'un arrivage
 * PUT    /api/arrivals/{id}                      - Modifier un arrivage
 * DELETE /api/arrivals/{id}                      - Supprimer un arrivage
 * GET    /api/arrivals/by-status/{status}        - Arrivages par statut
 * GET    /api/arrivals/by-country/{id}           - Arrivages par pays
 * POST   /api/arrivals/{id}/update-status        - Changer le statut
 * GET    /api/arrivals/stats                     - Stats des arrivages
 *
 * ITEMS (8):
 * GET    /api/items                              - Liste tous les items
 * POST   /api/items                              - Créer un item
 * GET    /api/items/{id}                         - Détail d'un item
 * PUT    /api/items/{id}                         - Modifier un item
 * DELETE /api/items/{id}                         - Supprimer un item
 * GET    /api/items/by-arrival/{id}              - Items par arrivage
 * GET    /api/items/by-product/{id}              - Items par produit
 * GET    /api/items/by-condition/{condition}     - Items par condition
 * POST   /api/items/bulk-create                  - Créer plusieurs items
 * GET    /api/items/stats                        - Stats des items
 *
 * DASHBOARD (10):
 * GET    /api/dashboard/metrics                  - Métriques générales
 * GET    /api/dashboard/recent-arrivals          - Arrivages récents
 * GET    /api/dashboard/recent-items             - Items récents
 * GET    /api/dashboard/inventory-health         - Santé du stock
 * GET    /api/dashboard/sales-analysis           - Analyse des ventes
 * GET    /api/dashboard/top-products             - Top produits
 * GET    /api/dashboard/top-suppliers            - Top fournisseurs
 * GET    /api/dashboard/top-countries            - Top pays
 * GET    /api/dashboard/condition-distribution   - Distribution par condition
 * GET    /api/dashboard/brand-analysis           - Analyse par marque
 *
 * RAPPORTS (4):
 * GET    /api/reports/inventory                  - Rapport inventaire
 * GET    /api/reports/arrivals                   - Rapport arrivages
 * GET    /api/reports/sales                      - Rapport ventes
 * GET    /api/reports/profitability              - Rapport rentabilité
 * GET    /api/exports/inventory                  - Export inventaire Excel
 * GET    /api/exports/arrivals                   - Export arrivages Excel
 *
 * TOTAL: 59 ENDPOINTS
 */

