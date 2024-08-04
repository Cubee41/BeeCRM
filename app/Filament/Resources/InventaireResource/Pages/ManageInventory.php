<?php

namespace App\Filament\Resources\InventaireResource\Pages;

use App\Models\Product;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\InventaireResource;

class ManageInventory extends Page
{
    protected static string $resource = InventaireResource::class;

    protected static string $view = 'filament.resources.inventaire-resource.pages.manage-inventory';


    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    public Collection $produits;

    public function mount()
    {
        $this->produits = Product::all();

    
    }


    // public function mount(): void
    // {
    //     static::authorizeResourceAccess();
    // }
}
