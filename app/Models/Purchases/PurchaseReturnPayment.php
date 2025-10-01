<?php

namespace App\Models\Purchases;

use App\Models\Outlet;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnPayment extends Model
{
    protected $fillable = [
        'purchase_return_id',
        'amount',
        'date',
        'reference',
        'payment_method',
        'note',
    ];

    public function purchase_return()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    protected static function booted()
    {
        static::created(function ($payment) {
            $payment->purchase_return?->recalculatePayment();
        });

        static::updated(function ($payment) {
            $payment->purchase_return?->recalculatePayment();
        });

        static::deleted(function ($payment) {
            $payment->purchase_return?->recalculatePayment();
        });
    }
}
