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
    protected static ?string $title = 'Daftar Penawaran Harga';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Buat Penawaran Harga')
                ->icon('heroicon-o-plus')
                ->url(fn() => url('/admin/' . Auth::user()->outlets()->first()->id . '/create-quotation'))
        ];
    }
}
