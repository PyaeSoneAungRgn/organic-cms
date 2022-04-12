<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Order';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'order_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('order_id')
                                    ->default('OG-' . random_int(100000, 999999))
                                    ->disabled()
                                    ->required()
                                    ->columnSpan([
                                        'default' => 2,
                                        'lg' => 1
                                    ]),
                                Forms\Components\BelongsToSelect::make('customer_id')
                                    ->relationship('customer', 'name')
                                    ->required()
                                    ->columnSpan([
                                        'default' => 2,
                                        'lg' => 1
                                    ]),
                                Forms\Components\RichEditor::make('note')
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'undo',
                                    ])
                                    ->columnSpan(2)
                            ])
                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('Products'),
                        Forms\Components\HasManyRepeater::make('order_products')
                            ->relationship('orderProducts')
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Product')
                                    ->options(Product::query()->pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->getSalePrice() ?? 0))
                                    ->columnSpan([
                                        'default' => 2,
                                        'md' => 2
                                    ]),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->integer()
                                    ->required()
                                    ->columnSpan([
                                        'default' => 2,
                                        'md' => 1
                                    ]),
                                Forms\Components\TextInput::make('unit_price')
                                    ->postfix('KS')
                                    ->numeric()
                                    ->disabled()
                                    ->required()
                                    ->columnSpan([
                                        'default' => 2,
                                        'md' => 1
                                    ]),
                            ])
                            ->dehydrated()
                            ->defaultItems(1)
                            ->disableLabel()
                            ->columns([
                                'md' => 4
                            ])
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->formatStateUsing(fn (string $state): string => (int) $state . ' Ks')
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
