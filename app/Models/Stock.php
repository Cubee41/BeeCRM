<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Wrapper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    public function product() 
    {
        return $this->belongsTo(Product::class);
    }

    public function wrapper() 
    {
        return $this->belongsTo(Wrapper::class);
    }
}
