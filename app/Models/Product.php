<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'description',
        'photo',
        'discount_min_qty',
        'discount',
        'stock_lower_limit',
        'stock_upper_limit',
        'custom',
    ];

    public function items()
    {
        return $this->hasMany(ItemsOrder::class);
    }


    public function getImage()
    {
        return $this->photo ? asset('storage/products/' . $this->photo) : asset('storage/producst/product_no_image.png');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getPrice()
    {
        return $this->discount_price ?? $this->price;
    }

}
