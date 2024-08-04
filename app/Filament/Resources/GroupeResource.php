<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupeResource\Pages;
use App\Filament\Resources\GroupeResource\RelationManagers;
use App\Models\Groupe;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GroupeResource extends Resource
{
    protected static ?string $model = Groupe::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Groupes';

    protected static ?string $modelLabel = 'Groupe des produits';

    protected static ?string $navigationGroup = 'Gestion des produits';

    protected static ?int $navigationSort = 2;


    protected static ?string $slug = 'groupes-produits';







    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(1),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                    Forms\Components\Textarea::make('description')
                    ->required(),
                
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\TextColumn::make('title'),

                Tables\Columns\TextColumn::make('description'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListGroupes::route('/'),
            'create' => Pages\CreateGroupe::route('/create'),
            // 'edit' => Pages\EditGroupe::route('/{record}/edit'),
        ];
    }
}
