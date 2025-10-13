<?php

namespace App\Models\Adjustments;

use App\Models\Outlet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Adjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_id',
        'reference',
        'note',
        'date'
    ];


    // Scope untuk filter by user outlets
    public function scopeForUserOutlets(Builder $query)
    {
        $user = Auth::user();

        if (!$user) {
            return $query;
        }

        // Jika user adalah owner, tampilkan semua data
        if ($user->hasRole('owner')) {
            return $query;
        }

        // Jika bukan owner, filter berdasarkan outlet user
        $outletIds = $user->outlets()->pluck('outlets.id');

        if ($outletIds->isEmpty()) {
            // Jika user tidak punya outlet, return query kosong
            return $query->where('outlet_id', 0);
        }

        return $query->whereIn('outlet_id', $outletIds);
    }

    public function products()
    {
        return $this->hasMany(AdjustedProduct::class, 'adjustment_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
