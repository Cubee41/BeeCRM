<?php

namespace App\Filament\Resources\WrapperResource\Pages;

use App\Filament\Resources\WrapperResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWrapper extends EditRecord
{
    protected static string $resource = WrapperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
