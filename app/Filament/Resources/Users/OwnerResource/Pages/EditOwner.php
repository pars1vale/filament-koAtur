<?php

namespace App\Filament\Resources\Users\OwnerResource\Pages;

use App\Filament\Resources\Users\OwnerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOwner extends EditRecord
{
    protected static string $resource = OwnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
