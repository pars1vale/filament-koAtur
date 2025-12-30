<?php

namespace App\Filament\Resources\Products\CategoryResource\Pages;

use App\Filament\Resources\Products\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;
    protected static ?string $title = 'Kategori';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Kategori Baru')
                ->modal(true)
                ->modalWidth('md')
                ->modalHeading('Buat Kategori'),
        ];
    }
}
