<?php

namespace App\Filament\Pages\Reports;

use Filament\Tables;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Sales\SalePayment;
use App\Models\Sales\SaleReturnPayment;
use App\Models\Purchases\PurchasePayment;
use App\Models\Purchases\PurchaseReturnPayment;

class PaymentsReport extends Page implements Tables\Contracts\HasTable, Forms\Contracts\HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $title = 'Laporan Pembayaran';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.reports.payments-report';

    public $start_date;
    public $end_date;
    public $payment_type = 'sales';
    public $payment_method;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(2)
                ->schema([
                    DatePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->reactive(),
                    DatePicker::make('end_date')
                        ->label('Tanggal Selesai')
                        ->reactive(),
                    Select::make('payment_type')
                        ->label('Tipe Pembayaran')
                        ->options([
                            'sales' => 'Penjualan',
                            'sales_return' => 'Retur Penjualan',
                            'purchase' => 'Pembelian',
                            'purchase_return' => 'Retur Pembelian',
                        ])
                        ->default('sales')
                        ->reactive(),
                    Select::make('payment_method')
                        ->label('Metode Pembayaran')
                        ->placeholder('Pilih Opsi')
                        ->options([
                            'cash' => 'Cash',
                            'credit_card' => 'Credit Card',
                            'bank' => 'Bank Transfer',
                            'cheque' => 'Cheque',
                            'other' => 'Lainnya',
                        ])
                        ->reactive(),
                ])
                ->columns(2),
        ];
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(fn() => $this->getFilteredQuery())
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y'),
                TextColumn::make('reference')
                    ->label('No. Referensi')
                    ->searchable(),
                TextColumn::make('related_reference')
                    ->label('Referensi Terkait')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR'),
                TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->badge(),
            ]);
    }

    protected function getFilteredQuery(): Builder
    {
        switch ($this->payment_type) {
            case 'sales':
                $query = SalePayment::query()
                    ->select('sale_payments.*')
                    ->addSelect(['related_reference' => function ($q) {
                        $q->select('reference')
                          ->from('sales')
                          ->whereColumn('sales.id', 'sale_payments.sale_id')
                          ->limit(1);
                    }]);
                break;

            case 'sales_return':
                $query = SaleReturnPayment::query()
                    ->select('sale_return_payments.*')
                    ->addSelect(['related_reference' => function ($q) {
                        $q->select('reference')
                          ->from('sale_returns')
                          ->whereColumn('sale_returns.id', 'sale_return_payments.sale_return_id')
                          ->limit(1);
                    }]);
                break;

            case 'purchase':
                $query = PurchasePayment::query()
                    ->select('purchase_payments.*')
                    ->addSelect(['related_reference' => function ($q) {
                        $q->select('reference')
                          ->from('purchases')
                          ->whereColumn('purchases.id', 'purchase_payments.purchase_id')
                          ->limit(1);
                    }]);
                break;

            case 'purchase_return':
                $query = PurchaseReturnPayment::query()
                    ->select('purchase_return_payments.*')
                    ->addSelect(['related_reference' => function ($q) {
                        $q->select('reference')
                          ->from('purchase_returns')
                          ->whereColumn('purchase_returns.id', 'purchase_return_payments.purchase_return_id')
                          ->limit(1);
                    }]);
                break;

            default:
                $query = SalePayment::query();
                break;
        }

        if ($this->start_date) {
            $query->whereDate('date', '>=', $this->start_date);
        }
        if ($this->end_date) {
            $query->whereDate('date', '<=', $this->end_date);
        }
        if ($this->payment_method) {
            $query->where('payment_method', $this->payment_method);
        }

        return $query;
    }
}