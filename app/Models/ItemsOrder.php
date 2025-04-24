<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsOrder extends Model
{

    public $timestamps = false;

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

}
