<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class SaleReturnPayment extends Model
{
    protected $fillable = [
        'sale_return_id',
        'amount',
        'date',
        'reference',
        'payment_method',
        'note',
    ];

    public function sale_return()
    {
        return $this->belongsTo(SaleReturn::class);
    }

    protected static function booted()
    {
        static::created(function ($payment) {
            $payment->sale_return?->recalculatePayment();
        });

        static::updated(function ($payment) {
            $payment->sale_return?->recalculatePayment();
        });

        static::deleted(function ($payment) {
            $payment->sale_return?->recalculatePayment();
        });
    }
}
