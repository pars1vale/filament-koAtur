<?php

namespace App\Models\Parties;

use App\Models\Outlet;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'city',
        'country',
        'address'
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
