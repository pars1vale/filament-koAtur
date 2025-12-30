<?php

namespace App\Filament\Resources\Users\OwnerResource\Pages;

use App\Filament\Resources\Users\OwnerResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateOwner extends CreateRecord
{
    protected static string $resource = OwnerResource::class;
    protected static ?string $title = 'Buat Pengguna';

    protected function afterCreate(): void
    {
        $this->record->outlets()->sync([
            Filament::getTenant()->id,
        ]);
    }
}
