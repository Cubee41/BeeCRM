<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at',
    ];



    public function products() 
    {
        return $this->belongsToMany(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderProducts(): HasMany
{
    return $this->hasMany(OrderProduct::class);
}

public function invoice()
        {
            return $this->belongsTo(Invoice::class);
        }
}
