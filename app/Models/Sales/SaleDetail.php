<?php

namespace App\Models\Sales;

use App\Models\Products\Product;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $table = 'sale_details';

    protected $fillable = [
        'id',
        'outlet_id',
        'sale_id',
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
                $model->outlet_id = Filament::getTenant()?->id;
            }
        });

        // Set outlet_id saat update
        static::updating(function ($model) {
            if (empty($model->outlet_id)) {
                $model->outlet_id = Filament::getTenant()?->id;
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function outlet()
    {
        return $this->belongsTo(\App\Models\Outlet::class);
    }
}
