<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';
    protected $fillable = [
        'currency_name', 
        'code', 
        'symbol', 
        'thousand_separator', 
        'decimal_separator', 
        'exchange_rate'
    ];
}
