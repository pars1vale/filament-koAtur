<?php

namespace App\Models\Products;

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
}
