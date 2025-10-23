<?php

namespace App\Models\Quotations;

use App\Models\Outlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Products\Product;

class QuotationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_id',
        'quotation_id',
        'product_id',
        'product_name',
        'product_code',
        'quantity',
        'price',
        'unit_price',
        'sub_total',
        'product_discount_amount',
        'product_discount_type',
        'product_tax_amount',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
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
