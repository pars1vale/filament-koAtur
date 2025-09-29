<?php

namespace App\Filament\Resources\Sales\SaleReturnResource\Pages;

use App\Filament\Resources\Sales\SaleReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSaleReturns extends ListRecords
{
    protected static string $resource = SaleReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
