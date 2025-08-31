<?php

namespace App\Filament\Resources\Expenses\CategoryResource\Pages;

use App\Filament\Resources\Expenses\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
