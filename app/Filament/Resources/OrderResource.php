<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                ->relationship('products', 'name')
                ->required()
                ->searchable()
                ->preload()
                ->required()
                ->label("Chose a product")
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('store_id')
                        ->relationship('store', 'name')
                        ->required()
                        ->label("Chose a Store"),
                    Forms\Components\Select::make('category')
                        ->options([
                            'product1' => 'Product1',
                            'product2' => 'Product2',
                            'product3' => 'Product3',
                        ]),
                    Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->inputMode('decimal'),
                    Forms\Components\RichEditor::make('description')
                        ->hint('What is this product ?')
                        ->hintColor('primary')

                ]),

                Forms\Components\TextInput::make('name')->label("Client Name"),
                Forms\Components\TextInput::make('tel'),
                Forms\Components\TextInput::make('wilaya'),
                Forms\Components\TextInput::make('commune'),
                Forms\Components\TextInput::make('adresse'),

                Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->inputMode('decimal'),

                Forms\Components\TextInput::make('price')
                ->numeric()
                ->inputMode('decimal'),

                Forms\Components\TextInput::make('total_price')
                ->numeric()
                ->inputMode('decimal'),


                Forms\Components\Select::make('status')
                ->options([
                    'Delivered' => 'Delivered',
                    'Cancelled' => 'Cancelled',
                    'No Response' => 'No Response',
                ]),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('tel'),
                Tables\Columns\TextColumn::make('wilaya'),
                Tables\Columns\TextColumn::make('commune'),
                Tables\Columns\TextColumn::make('adresse'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('total_price'),
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
