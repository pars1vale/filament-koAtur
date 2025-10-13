<?php

namespace App\Models\Purchases;

use App\Models\Outlet;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{

    protected $fillable = [
        'purchase_id',
        'outlet_id',
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

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    protected static function booted()
    {
        parent::boot();

        // Auto-generate reference saat create
        static::creating(function ($model) {

            // set outlet_id ke active_tenant
            if (empty($model->outlet_id)) {
                // Gunakan outlet aktif dari Filament Multi-tenancy
                $model->outlet_id = Filament::getTenant()?->id;
            }
        });

        // Saat update record
        static::updating(function ($model) {

            if (empty($model->outlet_id)) {
                // Gunakan outlet aktif dari Filament Multi-tenancy
                $model->outlet_id = Filament::getTenant()?->id;
            }
        });
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
