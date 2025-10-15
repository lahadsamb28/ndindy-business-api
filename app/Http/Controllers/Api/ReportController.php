<?php
// ==========================================
// app/Http/Controllers/Api/ReportController.php
// ==========================================

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExportService;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;
    protected $exportService;

    public function __construct(ReportService $reportService, ExportService $exportService)
    {
        $this->reportService = $reportService;
        $this->exportService = $exportService;
    }

    // GET /api/reports/inventory - Rapport inventaire
    public function inventory()
    {
        return response()->json([
            'success' => true,
            'data' => $this->reportService->generateInventoryReport()
        ]);
    }

    // GET /api/reports/arrivals - Rapport arrivages
    public function arrivals(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30));
        $endDate = $request->input('end_date', now());

        return response()->json([
            'success' => true,
            'data' => $this->reportService->generateArrivalsReport($startDate, $endDate)
        ]);
    }

    // GET /api/reports/sales - Rapport ventes
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30));
        $endDate = $request->input('end_date', now());

        return response()->json([
            'success' => true,
            'data' => $this->reportService->generateSalesReport($startDate, $endDate)
        ]);
    }

    // GET /api/reports/profitability - Rapport rentabilité
    public function profitability()
    {
        return response()->json([
            'success' => true,
            'data' => $this->reportService->generateProfitabilityReport()
        ]);
    }


    public function exportInventoryExcel(){
        return $this->exportService->exportInventoryReport();
    }
    public function exportArrivalsExcel(Request $request){
        $startDate = $request->start_date ?? now()->subDays(30);
        $endDate = $request->end_date ?? now();
        return $this->exportService->exportArrivalsReport($startDate, $endDate);
    }

    // public function exportCsv(Product $product){
    //     return $this->exportService->exportToCsv($product);
    // }
}
