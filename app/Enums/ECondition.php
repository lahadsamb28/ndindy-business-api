<?php

namespace App\Enums;

enum ECondition: string
{
    case NEW = 'new';
    case USED = 'used';
    case REFURBISHED = 'refurbished';

    public function label(): string
    {
        return match($this) {
            self::NEW => 'neuf',
            self::USED => 'occasion',
            self::REFURBISHED => 'reconditionné',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::NEW => 'green',
            self::USED => 'blue',
            self::REFURBISHED => 'orange',
        };
    }

    public function percentage(): int
    {
        return match($this) {
            self::NEW => 100,
            self::USED => 70,
            self::REFURBISHED => 85,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
