<?php

namespace App\Enums;

enum StatusArrival: string
{
    case PENDING = 'pending';
    case IN_TRANSIT = 'in_transit';
    case ARRIVED = 'arrived';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::IN_TRANSIT => 'En transit',
            self::ARRIVED => 'Arrivé',
            self::COMPLETED => 'Complété',
            self::CANCELLED => 'Annulé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::IN_TRANSIT => 'blue',
            self::ARRIVED => 'green',
            self::COMPLETED => 'gray',
            self::CANCELLED => 'red',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
