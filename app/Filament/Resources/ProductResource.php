<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Product';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->columnSpan([
                        'sm' => 2
                    ])
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set, $state) {
                                        $set('slug', str()->slug($state));
                                    }),
                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->required(),
                                Forms\Components\BelongsToSelect::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required(),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->integer()
                                    ->required(),
                                Forms\Components\Toggle::make('sell_on_market'),
                                Forms\Components\RichEditor::make('description')
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
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('images')
                                    ->multiple()
                                    ->disk('public')
                                    ->directory('products')
                                    ->visibility('public')
                                    ->columnSpan(2),
                            ])
                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->integer()
                            ->postfix('Ks')
                            ->required(),
                        Forms\Components\TextInput::make('discount_price')
                            ->numeric()
                            ->integer()
                            ->postfix('Ks'),
                        Forms\Components\DateTimePicker::make('discount_start_at'),
                        Forms\Components\DateTimePicker::make('discount_end_at'),
                    ])
                    ->columnSpan(1),
            ])
            ->columns([
                'sm' => 3,
                'lg' => null
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->formatStateUsing(fn (string $state): string => (int) $state . ' Ks')
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('sell_on_market'),
            ])
            ->filters([
                Tables\Filters\Filter::make('sell_on_market')
                    ->query(fn (Builder $query): Builder => $query->where('sell_on_market', true)),
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
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
