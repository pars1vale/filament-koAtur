<?php

namespace App\Filament\Resources\Expenses\CategoryResource\Pages;

use App\Filament\Resources\Expenses\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->modal(true)
            ->modalHeading('Create Expenses Category')
            ->modalWidth('lg'),
        ];
    }
}
