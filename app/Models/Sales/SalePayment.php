<?php

namespace App\Models\Sales;

use App\Models\Outlet;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    protected $fillable = [
        'sale_id',
        'outlet_id',
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
        // Set outlet_id saat create
        static::creating(function ($model) {
            if (empty($model->outlet_id)) {
                $model->outlet_id = Filament::getTenant()?->id;
            }
        });

        // Set outlet_id saat update
        static::updating(function ($model) {
            if (empty($model->outlet_id)) {
                $model->outlet_id = Filament::getTenant()?->id;
            }
        });

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
