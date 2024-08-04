<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use App\Models\Wrapper;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Subcategory;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Produits';

    protected static ?string $modelLabel = 'Nos produits';

    protected static ?string $slug = 'produits';




    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Caractéristiques du produit')
                ->schema([

                Forms\Components\TextInput::make('name')
                ->label('Nom du produit')
                ->required()
                ->maxLength(255),

                Forms\Components\Select::make('category_id')
                    ->relationship(name : 'category', titleAttribute : 'name')
                    ->preload()
                    ->live()
                    ->required(),
                    
    
                    Select::make('tags')
                    ->label('etiquettes')
                    ->multiple()
                    ->relationship('tags', 'name')
                    ->preload(),
                 
                ])->columns(2),

                Forms\Components\Section::make('Produit en casier')
                ->schema([

                Forms\Components\TextInput::make('buying_price')
                ->required()
                ->numeric()
                ->label("Prix d'achat"),

                Forms\Components\TextInput::make('unit_price')
                ->required()
                ->numeric()
                ->label('Prix unitaire'),

                Forms\Components\TextInput::make('available_quantity')
                    ->required()
                    ->label('Quantité disponible')
                    ->numeric(),

                Forms\Components\TextInput::make('stop_loss')
                    ->required()
                    ->label('Quantité seuil')
                    ->numeric(),
                ])->columns(4),
                
                
                // Forms\Components\Section::make('Emballage du produit')
                // ->description('Le paquet/l\'emballage/les casiers')
                // ->schema([
                
                //     Forms\Components\TextInput::make('wrapper_name')
                // ->label("Nom de l'emballage")
                // ->required()
                // ->maxLength(255),

                // Forms\Components\TextInput::make('wrapper_content')
                // ->required()
                // ->numeric()
                // ->label("Nombre par emballage"),

                // Forms\Components\TextInput::make('wrapper_price')
                // ->required()
                // ->numeric()
                // ->label("Prix de l'emballage"),


                // Forms\Components\TextInput::make('wrapper_available_quantity')
                //     ->required()
                //     ->label('Quantité disponible')
                //     ->numeric(),

                // Forms\Components\TextInput::make('wrapper_stop_loss')
                //     ->required()
                //     ->label('Quantité seuil')
                //     ->numeric(),
                // ])->columns(2),
                
                ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Nom')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                ->label('categorie')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subcategory.name')
                    ->label('sous-categorie')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('groupe.name')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('available_quantity')
                    ->label('Quantite disponible')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('buying_price')
                    ->label("Prix d'achat")
                    ->numeric(),

                Tables\Columns\TextColumn::make('unit_price')
                ->label('Prix unitaire')
                    ->numeric(),

                Tables\Columns\TextColumn::make('stop_loss')
                    ->label('Quantite alerte')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            TextEntry::make('name'),
            TextEntry::make('category.name'),
        ]);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
