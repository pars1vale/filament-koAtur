<?php

namespace App\Filament\Resources\Settings\UnitsResource\Pages;

use App\Filament\Resources\Settings\UnitsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnits extends EditRecord
{
    protected static string $resource = UnitsResource::class;
    protected static ?string $title = 'Ubah Satuan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
