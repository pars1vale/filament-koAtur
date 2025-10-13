<?php

namespace App\Models\Settings;

use App\Models\Outlet;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';
    protected $fillable = [
        'outlet_id',
        'name',
        'short_name',
        'operator',
        'operation_value'
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
