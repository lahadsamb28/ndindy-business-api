<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Base\BaseSummarySheet;
use App\Exports\Base\BaseDetailsSheet;
use App\Exports\Base\BaseChartSheet;

class GenericReportExport implements WithMultipleSheets
{
    public function __construct(
        private array $summaryData,
        private array $detailsData,
        private array $chartData,
        private string $chartTitle = 'Graphique'
    ) {}

    public function sheets(): array
    {
        return [
            new BaseSummarySheet($this->summaryData, 'Summary'),
            new BaseDetailsSheet($this->detailsData, 'Details'),
            new BaseChartSheet($this->chartData, $this->chartTitle),
        ];
    }
}
