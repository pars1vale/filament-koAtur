<?php

namespace App\Models\Purchases;

use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{

    protected $fillable = [
        'purchase_id',
        'amount',
        'date',
        'reference',
        'payment_method',
        'note',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    protected static function booted()
    {
        // Saat payment baru dibuat
        static::created(function ($payment) {
            $payment->purchase?->recalculatePayment();
        });

        // Saat payment diupdate
        static::updated(function ($payment) {
            $payment->purchase?->recalculatePayment();
        });

        // Saat payment dihapus
        static::deleted(function ($payment) {
            $payment->purchase?->recalculatePayment();
        });
    }
}
