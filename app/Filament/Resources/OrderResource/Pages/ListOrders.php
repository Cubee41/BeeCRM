<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Passer une commande'),
        ];
    }

    public function getTabs(): array{

        return [
            'Tous' => Tab::make(),
            'Cette semaine' => Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->where('created_at', '>=', now()->subWeek())),
            'Ce mois' => Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->where('created_at', '>=', now()->subMonth())),
        ];
    }
}
