<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsOrder extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',
        'custom',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

}
