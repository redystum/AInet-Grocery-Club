<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustments extends Model
{
    protected $table = 'stock_adjustments';

    protected $fillable = [
        'product_id',
        'registered_by_user_id',
        'quantity_changed',
        'custom',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }
}
