<?php
 namespace App\Services;

 use App\Models\Arrivals;
 use App\Models\Items;
 use Illuminate\Support\Collection;

 class ArrivalsService
 {
    public function createArrival(array $data)
    {
        $arrival = Arrivals::create($data);

        // Si des items sont fournis, les créer
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $itemData) {
                $itemData['arrivals_id'] = $arrival->id;
                Items::create($itemData);
            }
        }

        return $arrival;
    }

     public function getStatistics()
    {
        return [
            'total_arrivals' => Arrivals::count(),
            'total_spent' => Arrivals::sum('totalSpend'),
            'total_items' => Arrivals::sum('totalItems'),
            'average_spend' => Arrivals::avg('totalSpend'),
            'countries_count' => Arrivals::distinct('country_id')->count(),
            'suppliers_count' => Arrivals::distinct('supplier')->count(),
            'pending_arrivals' => Arrivals::where('status', 'pending')->count(),
            'completed_arrivals' => Arrivals::where('status', 'completed')->count()
        ];
    }
    public function getArrivalsByStatus($status): Collection
    {
        return Arrivals::where('status', $status)
            ->with(['country', 'item'])
            ->orderBy('arrival_date', 'desc')
            ->get();
    }

    public function getArrivalsByCountry($countryId)
    {
        return Arrivals::where('country_id', $countryId)
            ->with(['country', 'item'])
            ->paginate(15);
    }

    public function getArrivalsBetweenDates($startDate, $endDate)
    {
        return Arrivals::whereBetween('arrival_date', [$startDate, $endDate])
            ->with(['country', 'item'])
            ->orderBy('arrival_date', 'desc')
            ->get();
    }

    public function calculateMetrics(Arrivals $arrival)
    {
        return [
            'total_cost' => $arrival->purchaseCost + $arrival->shippingCost,
            'profit_margin' => (($arrival->totalSpend - $arrival->purchaseCost) / $arrival->totalSpend) * 100,
            'cost_per_item' => ($arrival->purchaseCost + $arrival->shippingCost) / $arrival->totalItems,
            'selling_price_per_item' => $arrival->totalSpend / $arrival->totalItems
        ];
    }

    public function getCountryStats()
    {
        return Arrivals::selectRaw('countries.name, count(arrivals.id) as count, SUM(arrivals.totalSpend) as total_value')
            ->join('countries', 'arrivals.country_id', '=', 'countries.id')
            ->groupBy('countries.id', 'countries.name')
            ->get();
    }

    public function getSupplierStats()
    {
        return Arrivals::selectRaw('supplier, count(id) as count, SUM(totalSpend) as total_value, AVG(totalSpend) as avg_value')
            ->groupBy('supplier')
            ->get();
    }
 }
