<?php

namespace App\Filament\Resources\Quotations\QuotationResource\Pages;

use App\Filament\Resources\Quotations\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuotation extends CreateRecord
{
    protected static string $resource = QuotationResource::class;
}
