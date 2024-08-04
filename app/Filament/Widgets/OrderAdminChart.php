<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class OrderAdminChart extends ChartWidget
{
    protected static ?string $heading = 'Evolution des commandes';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
            $data = Trend::model(Order::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();
     
        return [
            'datasets' => [
                [
                    'label' => 'commandes',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::createFromFormat('Y-m', $value->date)->locale('fr_FR')->isoFormat('MMMM')),
           
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
