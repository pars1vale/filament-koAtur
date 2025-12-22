<?php

namespace App\Filament\Resources\Outlets\TenantResource\Pages;

use App\Filament\Resources\Outlets\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
}
