<?php

namespace App\Filament\Resources\Settings\CurrenciesResource\Pages;

use App\Filament\Resources\Settings\CurrenciesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCurrencies extends ListRecords
{
    protected static string $resource = CurrenciesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->modal(true)
            ->modalHeading('Create Currency')
            ->modalWidth('md')
        ];
    }
}
