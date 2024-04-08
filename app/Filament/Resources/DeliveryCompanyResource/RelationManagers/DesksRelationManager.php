<?php

namespace App\Filament\Resources\DeliveryCompanyResource\RelationManagers;

use App\Models\Commune;
use App\Models\Daira;
use App\Models\Wilaya;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DesksRelationManager extends RelationManager
{
    protected static string $relationship = 'desks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('wilaya_id')
                ->options(Wilaya::query()->pluck('name_ascii', 'id'))
                ->searchable()
                // ->live() // Make this select reactive
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
                // ->dependsOn('wilaya_id') // Depends on the Wilaya selection
                // ->reactive() // Make this select also reactive for Commune to depend on
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
                // ->dependsOn('daira_id') // Depends on the Daira selection
                ->label('Commune'),

                Forms\Components\TextInput::make('address')
                ->required()
                ->label('Desk Address'),
                Forms\Components\TextInput::make('phone1')
                    ->tel()
                    ->label('Phone 1'),
                Forms\Components\TextInput::make('phone2')
                    ->tel()
                    ->label('Phone 2')->nullable(),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->label('Delivery Price'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('wilaya_id')
            ->columns([

              Tables\Columns\TextColumn::make('wilaya.code') ->label('Id'),
              Tables\Columns\TextColumn::make('wilaya.name_ascii')
                ->label('Wilaya')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('daira.name_ascii')
                ->label('Daira')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('commune.name_ascii')
                ->label('Commune')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('address')
                ->label('Desk Address'),

            Tables\Columns\TextColumn::make('phone1')
                ->label('Phone 1'),

            Tables\Columns\TextColumn::make('phone2')
                ->label('Phone 2'),

            Tables\Columns\TextColumn::make('price')
                ->label('Delivery Price'),


            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
