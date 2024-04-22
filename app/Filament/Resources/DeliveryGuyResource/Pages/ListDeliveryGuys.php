<?php

namespace App\Filament\Resources\DeliveryGuyResource\Pages;

use App\Filament\Resources\DeliveryGuyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryGuys extends ListRecords
{
    protected static string $resource = DeliveryGuyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
