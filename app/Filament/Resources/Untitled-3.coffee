Forms\Components\Section::make()
                    ->columns(1)
                    ->schema([
                        // Forms\Components\TextInput::make('subtotal')
                        // ->label('Total Hors taxe')
                        // ->numeric()
                        // ->readOnly()
                        // ->prefix('FCFA')
                        // ->afterStateHydrated(function(Get $get, Set $set)
                        // {
                        //     self::updateTotals($get, $set);
                        // }),

                        // Forms\Components\TextInput::make('TVA')
                        // ->numeric()
                        // ->suffix('%')
                        // ->disabled()
                        // ->live(true)
                        // ->default(18),
                        Forms\Components\Select::make('customer_id')
                    ->relationship(name : 'customer', titleAttribute : 'name')
                    ->label('client')
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
                        ->prefix('FCFA'),
                        
                    ])


                    
