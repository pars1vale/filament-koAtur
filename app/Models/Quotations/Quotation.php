<?php

namespace App\Models\Quotations;

use App\Models\Outlet;
use App\Models\Parties\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_id',
        'date',
        'reference',
        'customer_id',
        'customer_name',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'status',
        'note',
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

    public function details()
    {
        return $this->hasMany(QuotationDetail::class, 'quotation_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
