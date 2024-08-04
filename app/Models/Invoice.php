<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    
        /**
         * Get the user that owns the Invoice
         *
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function order()
        {
            return $this->belongsTo(Order::class);
        }
    
}
