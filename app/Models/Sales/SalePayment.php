<?php

namespace App\Models\Sales;

use App\Models\Outlet;
use App\Models\Sales\Sale;
use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{

    protected $fillable = [
        'sale_id',
        'amount',
        'date',
        'reference',
        'payment_method',
        'note',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
    protected static function booted()
    {
        // Saat payment baru dibuat
        static::created(function ($payment) {
            $payment->sale?->recalculatePayment();
        });

        // Saat payment diupdate
        static::updated(function ($payment) {
            $payment->sale?->recalculatePayment();
        });

        // Saat payment dihapus
        static::deleted(function ($payment) {
            $payment->sale?->recalculatePayment();
        });
    }
}
