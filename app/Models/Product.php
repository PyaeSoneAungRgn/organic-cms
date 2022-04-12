<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'sell_on_market',
        'description',
        'quantity',
        'price',
        'discount_price',
        'discount_start_at',
        'discount_end_at',
        'images'
    ];

    protected $casts = [
        'sell_on_market' => 'boolean',
        'discount_start_at' => 'datetime',
        'discount_end_at' => 'datetime',
        'images' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getSalePrice()
    {
        if (
            $this->discount_price
            && ($this->discount_start_at == null || $this->discount_start_at <= now())
            && ($this->discount_end_at == null || $this->discount_end_at >= now())
        ) {
            return $this->discount_price;
        }
        return $this->price;
    }
}
