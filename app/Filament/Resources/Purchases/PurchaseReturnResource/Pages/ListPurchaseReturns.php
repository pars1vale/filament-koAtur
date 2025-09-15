<?php

namespace App\Filament\Resources\Purchases\PurchaseReturnResource\Pages;

use App\Filament\Resources\Purchases\PurchaseReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseReturns extends ListRecords
{
    protected static string $resource = PurchaseReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
