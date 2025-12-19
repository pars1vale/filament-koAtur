<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products\Category;
use App\Models\Settings\Unit;
use App\Models\Outlet;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_id',
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

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    // Accessor
    public function getImageUrlAttribute()
    {
        return $this->product_image
            ? asset('storage/' . $this->product_image)
            : 'https://via.placeholder.com/150';
    }

    // Model Event
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            // Safety: generate automatically if it doesn't exist yet
            if (empty($product->product_code)) {
                $product->product_code = self::generateProductCode(
                    $product->outlet_id
                );
            }
        });
    }

    // Product Code Generator
    public static function generateProductCode(int $outletId): string
    {
        $prefix = 'PRD' . str_pad($outletId, 2, '0', STR_PAD_LEFT);

        $lastProduct = self::where('outlet_id', $outletId)
            ->where('product_code', 'like', $prefix . '-%')
            ->orderByRaw('CAST(SUBSTRING(product_code, -3) AS UNSIGNED) DESC')
            ->first();

        $lastNumber = $lastProduct
            ? (int) substr($lastProduct->product_code, -3)
            : 0;

        $nextNumber = $lastNumber + 1;

        return $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
