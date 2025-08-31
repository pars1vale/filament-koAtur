<?php

namespace App\Filament\Resources\Parties\SuppliersResource\Pages;

use App\Filament\Resources\Parties\SuppliersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuppliers extends ListRecords
{
    protected static string $resource = SuppliersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->modal(true)
            ->modalHeading('Create Supplier')
            ->modalWidth('md'),
        ];
    }
}
