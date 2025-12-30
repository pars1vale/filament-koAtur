<?php

namespace App\Filament\Resources\Settings\UnitsResource\Pages;

use App\Filament\Resources\Settings\UnitsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnits extends ListRecords
{
    protected static string $resource = UnitsResource::class;
    protected static ?string $title = 'Satuan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->modal(true)
            ->modalHeading('Buat Satuan')
            ->modalWidth('md')
        ];
    }
}
