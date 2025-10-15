<?php

namespace App\Exports\Base;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

abstract class BaseDetailsSheet implements FromArray, WithTitle, WithHeadings
{
    public function __construct(private array $data, private string $title = 'Details') {}

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return !empty($this->data) ? array_keys($this->data[0]) : [];
    }

    public function title(): string
    {
        return $this->title;
    }
}
