<?php

namespace App\Models\Adjustments;

use App\Models\Outlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Products\Product;

class AdjustedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_id',
        'adjustment_id',
        'product_id',
        'quantity',
        'type'
    ];

    public function adjustment()
    {
        return $this->belongsTo(Adjustment::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
