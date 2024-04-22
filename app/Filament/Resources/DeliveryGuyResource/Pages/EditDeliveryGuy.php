<?php

namespace App\Filament\Resources\DeliveryGuyResource\Pages;

use App\Filament\Resources\DeliveryGuyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeliveryGuy extends EditRecord
{
    protected static string $resource = DeliveryGuyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
