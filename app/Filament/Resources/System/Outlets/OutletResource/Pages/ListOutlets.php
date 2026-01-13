<?php

namespace App\Filament\Resources\Outlets\OutletResource\Pages;

use App\Filament\Resources\Outlets\OutletResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOutlets extends ListRecords
{
    protected static string $resource = OutletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
