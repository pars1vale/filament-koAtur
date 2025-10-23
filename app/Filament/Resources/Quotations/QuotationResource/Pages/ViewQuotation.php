<?php

namespace App\Filament\Resources\Quotations\QuotationResource\Pages;

use App\Models\Quotations\Quotation;
use App\Filament\Resources\Quotations\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ViewQuotation extends Page
{
    protected static string $resource = 'App\\Filament\\Resources\\Quotations\\QuotationResource';
    protected static string $view = 'filament.pages.quotations.view-quotation';

    public Quotation $quotation;

    public function mount($record)
    {
        $this->quotation = Quotation::with(['customer', 'details.product'])->findOrFail($record);
    }

    public function getTitle(): string
    {
        return 'Quotation Detail';
    }

    public function downloadPdf(): StreamedResponse
    {
        $quotation = $this->quotation;

        $pdf = Pdf::loadView('filament.resources.views.pdf.quotation', ['quotation' => $quotation]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $quotation->reference . '.pdf');
    }
}
