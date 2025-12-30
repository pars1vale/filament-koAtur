<?php

namespace App\Filament\Resources\Parties\SuppliersResource\Pages;

use App\Filament\Resources\Parties\SuppliersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuppliers extends EditRecord
{
    protected static string $resource = SuppliersResource::class;
    protected static ?string $title = 'Ubah Supplier';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
