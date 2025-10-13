<?php

namespace App\Models\Expenses;

use Illuminate\Database\Eloquent\Model;
use App\Models\Expenses\Category;
use App\Models\Outlet;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expense) {
            if (empty($expense->reference)) {
                $expense->reference = static::generateReference();
            }
        });
    }

    public static function generateReference()
    {
        $lastExpense = static::orderBy('id', 'desc')->first();
        $lastId = $lastExpense ? $lastExpense->id : 0;
        $nextId = $lastId + 1;

        return 'EXP' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
