<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Order;
use App\Models\Stock;
use Filament\Actions;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    

    protected function getHeaderActions(): array
{
    return [
        Action::make('Nouveau client')
    ->form([
        
        Forms\Components\TextInput::make('name')
        ->label('Nom ou Raison Sociale')
        ->required()
        ->maxLength(255)
        ->minLength(4),
    Forms\Components\TextInput::make('contact')
        ->label('contact')
        ->required()
        ->numeric()
        ->minLength(8),
        Forms\Components\TextInput::make('ifu')
        ->label('IFU')
        ->numeric()
        ->minLength(13),
    Forms\Components\TextInput::make('address')
        ->label('Adresse')
        ->required()
        ->minLength(2),
    Forms\Components\Hidden::make('account')
        ->label('Montant du compte')
        ->default(0),
        
    ])
    ->action(function (array $data){

       
        Customer::create([
            'name' => $data['name'],
            'contact' => $data['contact'],
            'ifu' => $data['ifu'],
            'address' => $data['address'],
            'account' => $data['account'],
            
         ]);
    })->requiresConfirmation()
    ->modalHeading('Créer un client')
    ->modalSubmitActionLabel('Enregistrer'),
        
    ];
}

    public function mutateFormDataBeforeCreate(array $data): array

    {

        $data['reste'] = $data['amount'] - $data['montant_recu'] > 0 ? $data['amount'] - $data['montant_recu'] : 0;

        $data['reglement'] = $data['reste'] == 0 ? 1 : 0;
        return $data;
    }

    protected function afterCreate(): void
    {

         $lastOrder = Order::latest()->with('orderProducts')->first();

         foreach($lastOrder->orderProducts as $productOrder)
         {
            $stock = Stock::where('product_id', '=', $productOrder->product_id)->first();
            $updateProduct = Product::where('id', '=', $productOrder->product_id)->first();

            $initialStock = $stock->new_stock;
            $moved = -$productOrder->quantity_detail;
            $updatedStock = $initialStock - $productOrder->quantity_detail;

            $stock->update([            
                    'product_id' => $productOrder->product_id,
                    'wrapper_id' => null,
                    'stock_initial' => $initialStock,
                    'moved' => $moved,
                    'new_stock' => $updatedStock,
                    'action' => 'mis a jour après commande',
                ]);
            
                $updateProduct->update([
                    'available_quantity' => $updatedStock
                ]);
            
         }

         $nfac = 'NVE' . rand(1000000, 9999999);
         
          Invoice::create([
             'num_facture' => $nfac,
             'order_id' => $lastOrder->id,
             'date' => now(),
             
          ]);
         
         
    }

     protected function getRedirectUrl(): string
     {
         return $this->previousUrl ?? $this->getResource()::getUrl('index');
     }
}
