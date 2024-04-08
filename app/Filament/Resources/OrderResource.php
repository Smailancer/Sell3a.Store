<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Daira;
use App\Models\Order;
use App\Models\Wilaya;
use App\Models\Commune;
use App\Models\Product;
use Filament\Forms\Form;
use App\Enums\OrderStatus;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\ProductsRelationManager;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-s-shopping-cart';


    protected static ?string $recordTitleAttribute = 'number';

    // protected static ?string $navigationGroup = 'Shop';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Order Details')
                            ->schema(static::getDetailsFormSchema())
                            ->description('I) Client Details')

                            ->columns(2),

                            Forms\Components\Section::make('Order Products')
                            ->description('II) Products Details')
                            ->schema([static::getItemsRepeater()
                            ->columns(2),
                            ]),
                    ])
                    ->columnSpan(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('name')->label("Client Name"),
                Tables\Columns\TextColumn::make('tel'),
                Tables\Columns\TextColumn::make('wilaya.name_ascii'),
                Tables\Columns\TextColumn::make('daira.name_ascii'),
                Tables\Columns\TextColumn::make('commune.name_ascii'),
                Tables\Columns\TextColumn::make('adresse'),
                // Tables\Columns\TextColumn::make('quantity'),
                // Tables\Columns\TextInputColumn::make('price'),

                // Tables\Columns\TextColumn::make('total_price'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    // protected function handleRecordCreation(array $data): Model
    // {
    // dd($data);
    //     return static::getModel()::create($data);
    // }


    public static function getRelations(): array
    {
        return [
            // ProductsRelationManager::class,
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

    public static function getDetailsFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('number')
                ->default('Sell3a-' . random_int(1000, 9999))
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32)
                ->unique(Order::class, 'number', ignoreRecord: true),

                Forms\Components\TextInput::make('name')->label('Client Name'),
                Forms\Components\TextInput::make('tel'),

                Forms\Components\Select::make('wilaya_id')
                ->options(Wilaya::query()->pluck('name_ascii', 'id'))
                ->searchable()
                ->label('Wilaya'),

                Forms\Components\Select::make('daira_id')
                ->options(function (callable $get) {
                    $wilayaId = $get('wilaya_id');
                    if (!$wilayaId) {
                        return [];
                    }
                    return Daira::where('wilaya_id', $wilayaId)->pluck('name_ascii', 'id');
                })
                ->searchable()
                ->label('Daira'),

                Forms\Components\Select::make('commune_id')
                ->options(function (callable $get) {
                    $dairaId = $get('daira_id');
                    if (!$dairaId) {
                        return [];
                    }
                    return Commune::where('daira_id', $dairaId)->pluck('name_ascii', 'id');
                })
                ->searchable()
                ->label('Commune'),



            Forms\Components\ToggleButtons::make('status')
                ->inline()
                ->options(OrderStatus::class)
                ->required(),

            TextInput::make('total_price')
                // ->disabled()
                // ->dehydrated(false) // Ensure this is not dehydrated so the value is sent to the server
                ->numeric()
                ->default(100)

                // ->required()
                ->columnSpan('md:3'),



                Forms\Components\TextInput::make('adresse'),

            // Forms\Components\Select::make('currency')
            //     ->searchable()
            //     ->getSearchResultsUsing(fn (string $query) => Currency::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
            //     ->getOptionLabelUsing(fn ($value): ?string => Currency::firstWhere('id', $value)?->getAttribute('name'))
            //     ->required(),



            Forms\Components\MarkdownEditor::make('note')
                ->columnSpan('full'),
        ];
    }
    // protected function afterSave(array $data, Model $record): void
    // {
    //     $productsData = $data['products']; // 'products' should match the name given in the repeater

    //     // Detach any existing items to handle the update case cleanly.
    //     $record->products()->detach();

    //     foreach ($productsData as $productData) {
    //         // Assuming $productData contains 'product_id', 'options', 'quantity', and 'price'
    //         $record->products()->attach($productData['product_id'], [
    //             'options' => json_encode($productData['options'] ?? []),
    //             'quantity' => $productData['quantity'],
    //             'price' => $productData['price'],
    //         ]);
    //     }
    // }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('orderProducts')
            ->relationship()
            ->schema([
                Select::make('product_id')
                    ->label('Product')
                    ->options(Product::query()->pluck('name', 'id'))
                    ->required()
                    ->live() // Ensure this input is reactive

                    ->columnSpan('md:5')
                    ->searchable()
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                    Select::make('option')
                        ->label('Option')
                        ->options(function (callable $get) {
                            $productId = $get('product_id');
                            if (!$productId) {
                                return [];
                            }

                            $product = Product::find($productId);
                            if (!$product || empty($product->options)) {
                                return [];
                            }

                            $optionsArray = $product->options;
                            $options = [];
                            foreach ($optionsArray as $key => $value) {
                                // Assuming $key is your option ID and $value is the price
                                $options[$key] = $key; // Display option keys as the selectable options
                            }

                            return $options;
                        })
                        ->afterStateUpdated(function ($state, $get, $set) {
                            $productId = $get('product_id');
                            if (!$productId) {
                                $set('price', 0);
                                return;
                            }

                            $product = Product::find($productId);
                            if (!$product || empty($product->options)) {
                                $set('price', 0);
                                return;
                            }

                            $selectedOptionPrice = $product->options[$state] ?? null;
                            $set('price', $selectedOptionPrice ?? 0);

                        })
                        ->reactive()
                        ->columnSpan('md:2'),

                        TextInput::make('price')
                        ->label('Unit Price')
                        // ->disabled()
                        // ->dehydrated(false) // Ensure this is not dehydrated so the value is sent to the server
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->columnSpan('md:3'),

                        TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->columnSpan('md:2'),

                        ])
                        ->extraItemActions([
                            // Action::make('openProduct')
                            //     ->tooltip('Open product')
                            //     ->icon('heroicon-s-shopping-cart')
                            // ->url(function (array $get) {
                                //     $productId = $get('product_id');
                                //     if (!$productId) {
                                    //         return null;
                                    //     }

                                    //     return ProductResource::getUrl('edit', ['record' => $productId]);
                                    // }, shouldOpenInNewTab: true)
                                    // ->hidden(fn (array $get) => blank($get('product_id'))),
                                    ])
                                    ->defaultItems(1)
                                    ->hiddenLabel()
                                    ->columns('md:10')
                                    ->required();

                                }


                            }
