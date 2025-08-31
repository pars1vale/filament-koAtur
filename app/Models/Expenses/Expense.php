<?php

namespace App\Models\Expenses;

use Illuminate\Database\Eloquent\Model;
use App\Models\Expenses\Category;

class Expense extends Model
{
    protected $table = 'expenses';

    protected $fillable = [
        'category_id',
        'date',
        'reference',
        'details',
        'amount',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
