<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Stock;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\StockResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StockResource\RelationManagers;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('wrapper_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('stock_initial')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('mouvement')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('new_stock')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('action')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                ->searchable()
                ->label('Produit')
                    ->numeric()
                    ->sortable(),
                    
                // Tables\Columns\TextColumn::make('wrapper.name')
                //     ->numeric(),
                Tables\Columns\TextColumn::make('stock_initial')
                ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric(),
                Tables\Columns\TextColumn::make('moved')
                ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric(),
                Tables\Columns\TextColumn::make('new_stock')
                    ->label('Quantite en stock')
                    ->numeric(),
                   
                    
                Tables\Columns\TextColumn::make('action')
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('date')
                ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //

                // TernaryFilter::make('Rupture de stock')
                //             ->placeholder('Tout')
                //             ->trueLabel('Oui')
                //             ->falseLabel('Non')
                //             ->queries(
                //             true: fn (Builder $query) => $query->where(self::castInt('new_stock'), '<=', ),
                //             false: fn (Builder $query) => $query->where(self::castInt('new_stock'), '>', ),
                //             )

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function castInt($param) : int {
        return (int)$param;
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStocks::route('/'),
            // 'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}
