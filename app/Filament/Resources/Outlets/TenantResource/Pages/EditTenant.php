<?php

namespace App\Filament\Resources\Outlets\TenantResource\Pages;

use App\Filament\Resources\Outlets\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
