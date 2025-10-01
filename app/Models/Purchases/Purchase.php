<?php

namespace App\Models\Purchases;

use App\Models\Outlet;
use App\Models\Parties\Supplier;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $with = ['product_details'];

    protected $fillable = [
        'id',
        'date',
        'reference',
        'supplier_id',
        'status',
        'payment_status',
        'payment_method',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'note',
    ];

    public function recalculatePayment()
    {
        $paid = $this->purchase_payments()->sum('amount');
        $this->paid_amount = min($paid, $this->total_amount);
        $this->due_amount  = $this->total_amount - $this->paid_amount;

        // Auto set payment status
        if ($this->due_amount == 0 && $this->paid_amount > 0) {
            $this->payment_status = 'paid';
        } elseif ($this->paid_amount > 0 && $this->due_amount > 0) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'unpaid';
        }

        $this->saveQuietly();
    }

    protected static function boot()
    {
        parent::boot();

        // Auto-generate reference saat create
        static::creating(function ($model) {
            if (!$model->reference) {
                $lastId = self::max('id') ?? 0;
                $nextId = $lastId + 1;

                $model->reference = 'PR' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }

            // Pastikan paid_amount tidak melebihi total
            if ($model->paid_amount > $model->total_amount) {
                $model->paid_amount = $model->total_amount;
            }

            // Set due
            $model->due_amount = $model->total_amount - $model->paid_amount;
        });

        // Saat update record
        static::updating(function ($model) {
            // Cek perubahan status
            $oldStatus = $model->getOriginal('status');
            $newStatus = $model->status;

            // Kalau status baru jadi completed → tambah stok
            if ($oldStatus !== 'completed' && $newStatus === 'completed') {
                foreach ($model->product_details as $detail) {
                    if ($detail->product) {
                        $detail->product->increment('product_quantity', $detail->quantity);
                    }
                }
            }

            // Kalau status sebelumnya completed tapi dibatalkan → kurangi stok
            if ($oldStatus === 'completed' && $newStatus !== 'completed') {
                foreach ($model->product_details as $detail) {
                    if ($detail->product) {
                        $detail->product->decrement('product_quantity', $detail->quantity);
                    }
                }
            }

            // Pastikan paid_amount tidak melebihi total
            if ($model->paid_amount > $model->total_amount) {
                $model->paid_amount = $model->total_amount;
            }

            // Set due
            $model->due_amount = $model->total_amount - $model->paid_amount;
        });
    }


    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function product_details()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id');
    }

    public function purchase_payments()
    {
        return $this->hasMany(PurchasePayment::class);
    }

        public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
