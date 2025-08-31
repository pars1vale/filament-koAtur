<?php

namespace App\Models\Parties;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $fillable = [
        'supplier_name',
        'supplier_email',
        'supplier_phone',
        'city',
        'country',
        'address'
    ];
}
