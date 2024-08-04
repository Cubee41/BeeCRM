<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Models\Stock;
use Filament\Actions;
use App\Models\Product;
use App\Models\Wrapper;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ProductResource;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterCreate(): void
    {

         $product = Product::latest()->first();
         Stock::create([
             'product_id' => $product->id,
             'wrapper_id' => null,
             'stock_initial' => $this->form->getState()['available_quantity'],
             'moved' => 0,
             'new_stock' => $this->form->getState()['available_quantity'],
             'action' => 'creation_detail',
             'date' => now(),
         ]);
        //    Wrapper::create([
        //        'name' => $this->form->getState()['wrapper_name'],
        //        'content' => $this->form->getState()['wrapper_content'],
        //        'prix' => $this->form->getState()['wrapper_price'],
        //        'quantite_disponible' => $this->form->getState()['wrapper_available_quantity'],
        //        'quantite_seuil' => $this->form->getState()['wrapper_stop_loss'],
        //        'product_id' => $product->id,
        //       ]);

        //  $wrapper = Wrapper::latest()->first();

        //  Stock::create([
        //      'product_id' => $product->id,
        //      'wrapper_id' => $wrapper->id,
        //      'stock_initial' => $this->form->getState()['wrapper_available_quantity'],
        //      'moved' => 0,
        //      'new_stock' => $this->form->getState()['wrapper_available_quantity'],
        //      'action' => 'creation_emballage',
        //      'date' => now(),
        //  ]);

       
    }
}
