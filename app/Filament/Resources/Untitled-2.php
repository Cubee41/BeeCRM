Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Card::make()
                   ->schema([
                        Forms\Components\Placeholder::make('Produits'),

                        Forms\Components\Repeater::make('orderProducts')
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
                     ]),
                    
                
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
                     ->label("Prix")
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
                
                ]),