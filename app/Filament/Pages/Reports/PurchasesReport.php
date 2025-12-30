<?php

namespace App\Filament\Pages\Reports;

use Filament\Tables;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Purchases\Purchase;
use App\Models\Parties\Supplier;

class PurchasesReport extends Page implements Tables\Contracts\HasTable, Forms\Contracts\HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $title = 'Laporan Pembelian';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.reports.purchases-report';

    public $start_date;
    public $end_date;
    public $supplier_id;
    public $status;
    public $payment_status;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(3)
                ->schema([
                    DatePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->reactive(),
                    DatePicker::make('end_date')
                        ->label('Tanggal Selesai')
                        ->reactive(),
                    Select::make('supplier_id')
                        ->label('Supplier')
                        ->placeholder('Pilih Opsi')
                        ->options(Supplier::pluck('supplier_name', 'id'))
                        ->searchable()
                        ->reactive(),
                ]),
            Forms\Components\Grid::make(2)
                ->schema([
                    Select::make('status')
                        ->label('Status')
                        ->placeholder('Pilih Opsi')
                        ->options([
                            'pending' => 'Pending',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ])
                        ->reactive(),
                    Select::make('payment_status')
                        ->label('Status Pembayaran')
                        ->placeholder('Pilih Opsi')
                        ->options([
                            'unpaid' => 'Belum Dibayar',
                            'paid' => 'Sudah Dibayar',
                            'partial' => 'Dibayar Sebagian',
                        ])
                        ->reactive(),
                ]),
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

                TextColumn::make('supplier.supplier_name')
                    ->label('Supplier'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR'),

                TextColumn::make('paid_amount')
                    ->label('Sudah Dibayar')
                    ->money('IDR'),

                TextColumn::make('due_amount')
                    ->label('Sisa Bayar')
                    ->money('IDR')
                    ->color(fn ($record) => $record->due_amount > 0 ? 'danger' : 'success'),

                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge(),
            ]);
    }

    protected function getFilteredQuery(): Builder
    {
        $query = Purchase::query()
            ->with('supplier');

        if ($this->start_date) {
            $query->whereDate('date', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->whereDate('date', '<=', $this->end_date);
        }

        if ($this->supplier_id) {
            $query->where('supplier_id', $this->supplier_id);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->payment_status) {
            $query->where('payment_status', $this->payment_status);
        }

        return $query;
    }
}