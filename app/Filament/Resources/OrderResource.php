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
                ->multiple()
                ->preload()
                ->label("Chose a product"),

                Forms\Components\KeyValue::make('client_info'),

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
                //
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
