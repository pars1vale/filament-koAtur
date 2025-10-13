<?php

namespace App\Models\Purchases;

use App\Models\Products\Product;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $table = 'purchase_details';

    protected $fillable = [
        'id',
        'outlet_id',
        'purchase_id',
        'product_id',
        'product_code',
        'quantity',
        'unit_price',
        'sub_total',
        'product_discount_amount',
        'product_discount_type',
        'product_tax_amount',
    ];

    protected $casts = [
        'quantity' => 'float',
        'unit_price' => 'float',
        'sub_total' => 'float',
        'product_discount_amount' => 'float',
        'product_tax_amount' => 'float',
    ];


    protected static function boot()
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
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}
