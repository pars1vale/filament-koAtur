<?php

namespace App\Models\Products;

use App\Models\Outlet;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected  $table = 'categories';
    protected $fillable = [
        'category_code',
        'category_name',
    ];
    protected $unique = [
        'category_code',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
