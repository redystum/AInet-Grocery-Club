<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{

    public const STATUS_PENDING = 'requested';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'product_id',
        'registered_by_user_id',
        'status',
        'quantity',
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
    
    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }
}
