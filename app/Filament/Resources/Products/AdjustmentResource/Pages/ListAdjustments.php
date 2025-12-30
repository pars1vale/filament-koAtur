<?php

namespace App\Filament\Resources\Products\AdjustmentResource\Pages;

use App\Filament\Resources\Products\AdjustmentResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListAdjustments extends ListRecords
{
    protected static string $resource = AdjustmentResource::class;
    protected static ?string $title = 'Penyesuaian';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Buat Penyesuaian')
                ->icon('heroicon-o-plus')
                ->url(fn() => url('/admin/' . Auth::user()->outlets()->first()->id . '/adjust-stock'))
        ];
    }
}
