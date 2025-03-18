<?php

namespace App\Filament\Widgets;

use App\Models\Shop\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class CustomersDonutChart extends ChartWidget
{
    use InteractsWithPageFilters;
    
    protected static ?string $heading = 'Customer Distribution';

    protected static ?int $sort = 3;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
      
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate'])->format('Y-m-d') :
            null;

           

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate'])->format('Y-m-d') :
            now();
            
        $orders = Order::selectRaw('currency, COUNT(currency) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('currency')
            ->get();

        $labels = $orders->pluck('currency')->toArray();
        $data = $orders->pluck('count')->toArray();

        return [
            'datasets' => [
                [
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
