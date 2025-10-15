<?php
// ==========================================
// app/Services/NotificationService.php
// ==========================================

namespace App\Services;

use App\Models\Product;

class NotificationService
{
    public function checkLowStockAlerts()
    {
        $lowStockProducts = Product::where('quantity', '<', 20)
            ->where('quantity', '>', 0)
            ->get();

        $outOfStock = Product::where('quantity', 0)->get();

        return [
            'low_stock' => $lowStockProducts->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'quantity' => $p->quantity,
                'alert_level' => 20
            ]),
            'out_of_stock' => $outOfStock->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name
            ])
        ];
    }

    public function sendLowStockNotification($product)
    {
        // À implémenter avec vos préférences de notification
        // Ex: Email, SMS, Push, etc.

        return [
            'status' => 'sent',
            'product' => $product->name,
            'quantity' => $product->quantity
        ];
    }
}
