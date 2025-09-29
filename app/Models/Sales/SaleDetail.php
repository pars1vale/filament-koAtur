<?php

namespace App\Models\Sales;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $table = 'sale_details';

    protected $fillable = [
        'id',
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

    public function product()
    {
        return $this->belongsTo(Product::class, 'sale_id');
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
