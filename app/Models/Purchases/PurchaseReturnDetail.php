<?php

namespace App\Models\Purchases;

use App\Models\Products\Product;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnDetail extends Model
{
    protected $table = 'purchase_return_details';

    protected $fillable = [
        'id',
        'outlet_id',
        'purchase_return_id',
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
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function purchase_return()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    public function outlet()
    {
        return $this->belongsTo(\App\Models\Outlet::class);
    }
}
