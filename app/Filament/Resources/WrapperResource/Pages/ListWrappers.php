<?php

namespace App\Filament\Resources\WrapperResource\Pages;

use App\Filament\Resources\WrapperResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrappers extends ListRecords
{
    protected static string $resource = WrapperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
