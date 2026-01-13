<?php

namespace App\Filament\Resources\System\Users\UserResource\Pages;

use App\Filament\Resources\System\Users\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
