<?php

namespace App\Filament\Resources\Parties\CustomerResource\Pages;

use App\Filament\Resources\Parties\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
