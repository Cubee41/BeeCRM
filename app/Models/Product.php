<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Order;
use App\Models\Groupe;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [

        'name',
        'available_quantity',
        'unit_price',
        'stop_loss',
        'category_id',
        'subcategory_id',
        'groupe_id',

    ];

    /**
     * Get the category that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }

    public function tags() 
    {
        return $this->belongsToMany(Tag::class);
    }

    public function orders() 
    {
        return $this->belongsToMany(Order::class);
    }

}
