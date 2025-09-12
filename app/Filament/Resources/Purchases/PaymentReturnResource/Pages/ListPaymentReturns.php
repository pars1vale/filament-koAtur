<?php

namespace App\Filament\Resources\Purchases\PaymentReturnResource\Pages;

use App\Filament\Resources\Purchases\PaymentReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentReturns extends ListRecords
{
    protected static string $resource = PaymentReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
