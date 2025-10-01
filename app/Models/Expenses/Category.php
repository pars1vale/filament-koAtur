<?php

namespace App\Models\Expenses;

use Illuminate\Database\Eloquent\Model;
use App\Models\Expenses\Expense;
use App\Models\Outlet;

class Category extends Model
{
    protected  $table = 'expense_categories';
    protected $fillable = [
        'category_name',
        'category_description',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }

        public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
