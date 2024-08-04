<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventaireResource\Pages;
use App\Filament\Resources\InventaireResource\RelationManagers;
use App\Models\Inventaire;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventaireResource extends Resource
{
    protected static ?string $model = Inventaire::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('product.name')
                ->label('Produit')
                    ->numeric()
                    ->sortable(),
                    
                // Tables\Columns\TextColumn::make('wrapper.name')
                //     ->numeric(),
                Tables\Columns\TextColumn::make('stock_initial')
                    ->label('stock')
                    ->numeric(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            // 'index' => Pages\ManageInventaires::route('/'),
            'index' => Pages\ManageInventory::route('/'),
        ];
    }
}
