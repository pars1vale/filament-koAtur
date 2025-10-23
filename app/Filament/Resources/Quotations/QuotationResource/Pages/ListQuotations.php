<?php

namespace App\Filament\Resources\Quotations\QuotationResource\Pages;

use App\Filament\Resources\Quotations\QuotationResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListQuotations extends ListRecords
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Create Quotations')
                ->icon('heroicon-o-plus')
                ->url(fn() => url('/admin/' . Auth::user()->outlets()->first()->id . '/create-quotation'))
        ];
    }
}
