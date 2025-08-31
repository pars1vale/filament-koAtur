<?php

namespace App\Filament\Resources\Settings\CurrenciesResource\Pages;

use App\Filament\Resources\Settings\CurrenciesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCurrencies extends EditRecord
{
    protected static string $resource = CurrenciesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
