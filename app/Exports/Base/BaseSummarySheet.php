<?php

namespace App\Exports\Base;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

abstract class BaseSummarySheet implements FromArray, WithTitle
{
    public function __construct(private array $data, private string $title= 'Summary') {}

    public function array(): array
    {
        return collect($this->data)
            ->map(fn($v, $k) => [$k, $v])
            ->toArray();
    }

    public function title(): string
    {
        return $this->title;
    }
}
