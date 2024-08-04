<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Groupe extends Model
{
    use HasFactory;

    protected $fillable = [

        'name',
        'title',
        'description'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }
}
