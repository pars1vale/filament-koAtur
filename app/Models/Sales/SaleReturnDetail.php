<?php

namespace App\Models\Sales;

use App\Models\Products\Product;
use App\Models\Sales\Sale;
use Illuminate\Database\Eloquent\Model;

class SaleReturnDetail extends Model
{
    protected $table = 'sale_return_details';

    protected $fillable = [
        'id',
        'sale_return_id',
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
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function sale()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id');
    }
}
