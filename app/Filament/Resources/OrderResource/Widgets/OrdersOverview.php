<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OrdersOverview extends BaseWidget
{
    protected function getCards(): array
    {

        $card = [
            Card::make('En attente', Order::where('status', '=', 'processing'))
            ->description("Commandes en attente")
            ->descriptionIcon('heroicon-s-trending-up')
            ->color('primary')
            ->url('/admin/orders'),

            
            
        ];


        return $card;
    }
}
