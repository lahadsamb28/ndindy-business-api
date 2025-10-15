<?php

// ==========================================
// app/Http/Controllers/Api/AlertController.php
// ==========================================

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;

class AlertController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getLowStockAlerts()
    {
        $alerts = $this->notificationService->checkLowStockAlerts();

        return response()->json([
            'success' => true,
            'data' => [
                'low_stock' => $alerts['low_stock'],
                'out_of_stock' => $alerts['out_of_stock'],
                'total_alerts' => count($alerts['low_stock']) + count($alerts['out_of_stock'])
            ]
        ]);
    }

    public function dismissAlert($productId)
    {
        // Logique pour marquer une alerte comme lue
        return response()->json([
            'message' => 'Alerte marquée comme lue'
        ]);
    }
}
