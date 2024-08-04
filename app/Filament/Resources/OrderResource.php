<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Modal;
use Filament\Button;


use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use App\Models\Wrapper;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\OrderProduct;
use Filament\Forms\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Filament\Resources\Action;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ColorEntry;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use App\Filament\Resources\OrderResource\Widgets\OrdersOverview;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Commandes';

    protected static ?string $modelLabel = 'commandes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                    Section::make()
                    ->schema([
                        Group::make()
                        ->schema([
                            Card::make()
                            ->schema([
                                Forms\Components\Select::make('customer_id')
                                ->relationship(name : 'customer', titleAttribute : 'name')
                                ->label('client')
                                ->searchable()
                                ->preload()
                                ->required(),

                                Forms\Components\TextInput::make('amount')
                                ->label('Total TTC') 
                                ->numeric()
                                ->readOnly()
                                ->prefix('FCFA')
                                ->afterStateHydrated(function(Get $get, Set $set)
                                {
                                    self::updateTotals($get, $set);
                                }),

                                Forms\Components\TextInput::make('montant_recu')
                                ->label('Montant recu') 
                                ->numeric()
                                ->gte('amount')
                                ->prefix('FCFA')
                                ->required()
                                ->validationMessages([
                                    'gte' => 'Le montant recu est inférieur au Total TTC',
                                    'required' => 'Vous devez mentionner le montant recu'
                                ]),
                            ]),
                        ])->columns(1),

                        Group::make()
                        ->schema([
                            Card::make()
                            ->schema([
                                Forms\Components\Repeater::make('orderProducts')
                                ->label('Selectionner les produits')
                    ->schema([
                    Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                     ->options(
                        Product::query()->pluck('name', 'id')
                        
                        )
                    ->disableOptionWhen(function ($value, $state, Get $get)
                    {
                        return collect($get('../*.product_id'))
                                    ->reject(fn($id) => $id == $state)
                                    ->filter()
                                    ->contains($value);
                    })
                     ->live()
                     ->required()
                     ->afterStateUpdated(function ($state, callable $set){
                        $product = Product::find($state);
                        if($product) {
                            $set('price', $product->unit_price);
                            $set('stock_quantity', $product->available_quantity);
                        }
                     })
                     ->columnSpan([
                        'md' => 3,
                     ])
                     ->label('Produit'),
                    
                
                     Forms\Components\TextInput::make('quantity_detail')
                     ->required()
                     ->label("Nombre de casier")
                     ->numeric()
                     ->live(onBlur : true)
                     ->afterStateUpdated(function(Set $set, Get $get, $state){

                        $totalbyproduct = (int)$get('price') * $state;

                        $set('amount_detail', $totalbyproduct);
                    }) ->lte('stock_quantity')
                     ->columnSpan([
                         'md' => 2,
                        ])
                        ->validationMessages([
                            'lte' => 'La quantité en stock est insuffisante',
                        ]),

                     Forms\Components\TextInput::make('price')
                     ->required()
                     ->live()
                     ->label("Prix unitaire du produit")
                     ->columnSpan([
                        'md' => 1,
                     ])
                     ->disabled()
                     ->dehydrated(false),

                     Forms\Components\Hidden::make('stock_quantity')
                     ->required()
                     ->live()
                     ->columnSpan([
                        'md' => 1,
                     ])
                     ->disabled()
                     ->dehydrated(false),


                     Forms\Components\Hidden::make('amount_detail')
                     ->label("Total produit")
                     ->columnSpan([
                        'md' => 2,
                     ])
                     ->disabled(),
                    ])
                ->live()
                ->afterStateUpdated(function (Get $get, Set $set){
                   Self::updateTotals($get, $set);
                })
    // ->deleteAction(
    //    fn(Action $action) => $action->after(fn(Get $get, Set $set) => self::updateTotals($get, $set)),
    // )
                ->minItems(1)
                ->relationship()
                ->reorderableWithDragAndDrop(false)
                ->addActionLabel('Ajouter'),
                            ]),
                        ])->columns(1),
                    ])->columns(2),
                   
                ]);
    }

    public static function updateTotals(Get $get, Set $set): void
    {
        $selectedProducts = collect($get('orderProducts'))
        ->filter(fn($item) => !empty($item['product_id']) && !empty($item['quantity_detail']));

        $prices = Product::find($selectedProducts->pluck('product_id'))->pluck('unit_price', 'id');


        $subTotal = $selectedProducts->reduce(function ($subTotal, $product) use ($prices) {
            return $subTotal + ($prices[$product['product_id']] * $product['quantity_detail']);
        }, 0);
        
        $subtotalFormatted = number_format($subTotal, 0, '', '');
        // $totalttcformatted = number_format($subTotal + ($subTotal * ($get('TVA') / 100)), 0, '', '');
        
        // Utilisation de la variable $set pour assigner la valeur
        // $set('subtotal', $subtotalFormatted);

        //  $subTotal = $selectedProducts->reduce(function ($subTotal, $product) use ($prices)
        //  {
        //     return $subTotal + ($prices[$product['product_id']] * $product['quantity']);
             
        //  }, 0);

        //  $set('subtotal') = number_format($subTotal, 2, '.', '');
        $set('amount', $subtotalFormatted);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('Numero'),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Date commande')
                ->dateTime()
                ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Client'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->numeric(),
                
                Tables\Columns\IconColumn::make('reglement')
                ->boolean()
                ->label('Réglé'),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            TextEntry::make('customer.name')->label('client'),
            TextEntry::make('created_at')->label('Date de la commande'),
            TextEntry::make('amount')->size(8)->label('Total Commande'),

            
        ]);
    }



    public static function getRelations(): array
    {
        return [
            //
        ];
    }



    public static function getWidgets(): array
    {
        return [
            OrdersOverview::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            // 'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
 