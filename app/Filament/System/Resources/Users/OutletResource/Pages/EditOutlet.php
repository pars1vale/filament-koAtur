<?php

namespace App\Filament\System\Resources\OutletResource\Pages;

use App\Filament\System\Resources\OutletResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOutlet extends EditRecord
{
    protected static string $resource = OutletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
