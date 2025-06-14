<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operations extends Model
{

    const TYPE_DEBIT = "debit";
    const TYPE_CREDIT = "credit";

    const TYPE_DEBIT_ORDER = "order";
    const TYPE_DEBIT_MEMBERSHIP_FEE = "membership_fee";
    const TYPE_CREDIT_PAYMENT = "payment";
    const TYPE_CREDIT_ORDER_CANCEL = "order_cancellation";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'card_id',
        'type',
        'value',
        'date',
        'debit_type',
        'credit_type',
        'payment_type',
        'payment_reference',
        'order_id',
        'custom'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'custom' => 'array'
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
