<?php

namespace App\Filament\Resources\Purchases\PaymentReturnResource\Pages;

use App\Filament\Resources\Purchases\PaymentReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentReturn extends EditRecord
{
    protected static string $resource = PaymentReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
