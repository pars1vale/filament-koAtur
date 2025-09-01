<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Unit;
use App\Models\Products\Category;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'product_name',
        'product_code',
        'product_barcode_symbology',
        'product_quantity',
        'product_cost',
        'product_price',
        'unit_id',
        'product_stock_alert',
        'product_order_tax',
        'product_tax_type',
        'product_note',
        'product_image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
