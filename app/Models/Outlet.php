<?php

namespace App\Models;

use App\Models\Adjustments\Adjustment;
use App\Models\Expenses\Category as ExpensesCategory;
use App\Models\Expenses\Expense;
use App\Models\Parties\Customer;
use App\Models\Parties\Supplier;
use App\Models\Products\Category;
use App\Models\Products\Product;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\PurchasePayment;
use App\Models\Purchases\PurchaseReturn;
use App\Models\Purchases\PurchaseReturnPayment;
use App\Models\Sales\Sale;
use App\Models\Sales\SalePayment;
use App\Models\Sales\SaleReturn;
use App\Models\Sales\SaleReturnPayment;
use App\Models\Settings\Currency;
use App\Models\Settings\Setting;
use App\Models\Settings\Unit;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'outlet_user')
            ->withTimestamps();
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }

    public function expense_categories()
    {
        return $this->hasMany(ExpensesCategory::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    public function purchases_payment()
    {
        return $this->hasMany(PurchasePayment::class);
    }

    public function purchases_return()
    {
        return $this->hasMany(PurchaseReturn::class);
    }

    public function purchases_return_payment()
    {
        return $this->hasMany(PurchaseReturnPayment::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function sales_payment()
    {
        return $this->hasMany(SalePayment::class);
    }

    public function sales_return()
    {
        return $this->hasMany(SaleReturn::class);
    }

    public function sales_return_payment()
    {
        return $this->hasMany(SaleReturnPayment::class);
    }

    public function currencies()
    {
        return $this->hasMany(Currency::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }
}
