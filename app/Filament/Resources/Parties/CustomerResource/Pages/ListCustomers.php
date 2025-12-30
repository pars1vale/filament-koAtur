<?php

namespace App\Filament\Resources\Parties\CustomerResource\Pages;

use App\Filament\Resources\Parties\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;
    protected static ?string $title = 'Pelanggan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modal(true)
                ->modalHeading('Buat Pelanggan')
                ->modalWidth('md')
        ];
    }
}
