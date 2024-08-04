<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Commandes', Order::query()->where('reglement', '=', 'false')->count())
            ->description('Commandes non réglé')
            ->color('danger'),
            Stat::make('Stock', Order::query()->where('created_at', '>=', now()->subWeek())->count())
            ->description('Total commandes de cette semaine')
            ->color('primary'),
            Stat::make('Stock', Product::whereColumn('available_quantity', '<', 'stop_loss')->count())
            ->description('Produit(s) en rupture de stock')
            ->color('danger'),
            
        ];
    }
}
