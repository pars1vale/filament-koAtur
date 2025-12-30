<?php

namespace App\Filament\Resources\Sales\SaleReturnPaymentResource\Pages;

use App\Filament\Resources\Sales\SaleReturnPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSaleReturnPayment extends CreateRecord
{
    protected static string $resource = SaleReturnPaymentResource::class;
    protected static ?string $title = 'Buat Pengembalian Pembayaran';
}
