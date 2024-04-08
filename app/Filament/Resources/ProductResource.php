<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-s-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\Select::make('store_id')
                ->relationship('store', 'name')
                ->required()
                ->label("Chose a Store"),

                Forms\Components\Select::make('category')
                ->options([
                    'Electronics' => 'Electronics',
                    'Cloths' => 'Cloths',
                    'Pots' => 'Pots',
                ]),

                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->label("Name Of The Product"),

                Forms\Components\TextInput::make('price')
                ->numeric()
                ->inputMode('decimal')
                ->label('Default Price'),

                Forms\Components\MarkdownEditor::make('description')
                ->hint('What is this product ?')
                ->hintColor('primary'),

                Forms\Components\KeyValue::make('options')
                ->addActionLabel('Add Option')
                ->keyLabel('Option')
                ->ValueLabel('Price'),

                // Forms\Components\Repeater::make('options')
                // ->schema([
                //     TextInput::make('option')->required(),
                //     TextInput::make('price')->required()->numeric(),
                // ])
                // ->columns(2),

                Forms\Components\FileUpload::make('image')
                ->multiple()
                ->image()
                ->imageEditor()
                ->reorderable()
                // ->moveFiles()
                // ->storeFiles(false)
                ->maxFiles(5),






            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),

                Tables\Columns\ImageColumn::make('image')
                ->circular()
                ->extraImgAttributes(['loading' => 'lazy'])
                ->circular()
                ->stacked()
                ->overlap(4)
                ->ring(5),

                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                Tables\Columns\TextColumn::make('store.name'),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('price')->sortable(),


            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                ->options([
                    'product1' => 'Product1',
                    'product2' => 'Product2',
                    'product3' => 'Product3',
                ]),

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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
