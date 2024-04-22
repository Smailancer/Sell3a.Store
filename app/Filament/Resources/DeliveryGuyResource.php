<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryGuyResource\Pages;
use App\Filament\Resources\DeliveryGuyResource\RelationManagers;
use App\Models\DeliveryGuy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryGuyResource extends Resource
{
    protected static ?string $model = DeliveryGuy::class;

    protected static ?string $navigationIcon = 'gmdi-delivery-dining-r';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
                Forms\Components\Textarea::make('note')
                ->required()
                ->autosize(),
                Forms\Components\TextInput::make('phone1')
                ->tel()
                ->label('Phone 1'),
                Forms\Components\TextInput::make('phone2')
                ->tel()
                ->label('Phone 2')->nullable(),
                // Forms\Components\TextInput::make('price')
                // ->numeric()
                // ->label('Delivery Price'),
                Forms\Components\FileUpload::make('logo')
                ->avatar()
                ->disk('public')
                ->directory('images/logo/Delivery') ,

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                ->circular(),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('note'),
                Tables\Columns\TextColumn::make('phone1')
                ->label('Phone 1'),

                  Tables\Columns\TextColumn::make('phone2')
                ->label('Phone 2'),
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
            'index' => Pages\ListDeliveryGuys::route('/'),
            'create' => Pages\CreateDeliveryGuy::route('/create'),
            'edit' => Pages\EditDeliveryGuy::route('/{record}/edit'),
        ];
    }
}
