<?php
namespace App\Exports\Base;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class BaseChartSheet implements FromArray, WithCharts, WithTitle
{
    public function __construct(
        private array $chartData,
        private string $chartTitle = 'Graphique'
    ) {}

    public function array(): array
    {
        return [
            ['Catégorie', 'Valeur'],
            ...collect($this->chartData)->map(fn($v, $k) => [$k, $v])->toArray()
        ];
    }

    public function charts()
    {
        $categories = [new DataSeriesValues('String', "{$this->chartTitle}!A2:A100")];
        $values = [new DataSeriesValues('Number', "{$this->chartTitle}!B2:B100")];

        $series = new DataSeries(
            DataSeries::TYPE_PIECHART,
            null,
            range(0, count($values) - 1),
            [],
            $categories,
            $values
        );

        $plot = new PlotArea(null, [$series]);

        $chart = new Chart(
            $this->chartTitle,
            new Title($this->chartTitle),
            new Legend(Legend::POSITION_RIGHT, null, false),
            $plot
        );

        $chart->setTopLeftPosition('D2');
        $chart->setBottomRightPosition('L20');

        return $chart;
    }

    public function title(): string
    {
        return $this->chartTitle;
    }
}
