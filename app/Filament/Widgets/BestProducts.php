<?php

namespace App\Filament\Widgets;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class BestProducts extends BaseWidget
{
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::withCount('orders')
            ->with('category')
            ->orderByDesc('orders_count'))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('nom'),
                Tables\Columns\TextColumn::make('orders_count')->label('Compte'),
                Tables\Columns\TextColumn::make('category.name')->label('Categorie'),
            ])->filters([
                SelectFilter::make('categorie')
                ->relationship('category', 'name')
                ->searchable(),
                Filter::make('periode')
    ->form([
        Forms\Components\DatePicker::make('du'),
        Forms\Components\DatePicker::make('au'),
    ])
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when(
                $data['du'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
            )
            ->when(
                $data['au'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
            );
    })
            ]);
    }
}
