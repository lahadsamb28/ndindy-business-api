<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case STAFF = 'staff';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrateur',
            self::MANAGER => 'Gestionnaire',
            self::STAFF => 'Personnel',
        };
    }

    public function permissions(): array
    {
        return match($this) {
            self::ADMIN => ['*'], // Toutes les permissions
            self::MANAGER => [
                'products.view',
                'products.create',
                'products.update',
                'arrivals.view',
                'arrivals.create',
                'arrivals.update',
                'items.view',
                'items.create',
                'items.update',
                'reports.view'
            ],
            self::STAFF => [
                'products.view',
                'arrivals.view',
                'items.view'
            ],
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
