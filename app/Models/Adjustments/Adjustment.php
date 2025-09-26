<?php

namespace App\Models\Adjustments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'note',
        'date'
    ];

    public function products()
    {
        return $this->hasMany(AdjustedProduct::class, 'adjustment_id');
    }
}
