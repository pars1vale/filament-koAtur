<?php

namespace App\Models\Purchases;

use App\Models\Outlet;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnPayment extends Model
{
    protected $fillable = [
        'purchase_return_id',
        'outlet_id',
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
        // Set outlet_id saat create
        static::creating(function ($model) {
            if (empty($model->outlet_id)) {
                // Gunakan outlet aktif dari Filament Multi-tenancy
                $model->outlet_id = Filament::getTenant()?->id;
            }
        });

        // Set outlet_id saat update
        static::updating(function ($model) {
            if (empty($model->outlet_id)) {
                // Gunakan outlet aktif dari Filament Multi-tenancy
                $model->outlet_id = Filament::getTenant()?->id;
            }
        });

        // Event handlers untuk recalculate payment
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
