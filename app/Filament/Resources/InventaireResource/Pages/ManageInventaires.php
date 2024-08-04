<?php

namespace App\Filament\Resources\InventaireResource\Pages;

use App\Filament\Resources\InventaireResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageInventaires extends ManageRecords
{
    protected static string $resource = InventaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    
}
