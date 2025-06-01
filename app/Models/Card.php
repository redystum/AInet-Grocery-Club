<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    const PAYMENT_TYPE_VISA = 'Visa';
    const PAYMENT_TYPE_PAYPAL = 'PayPal';
    const PAYMENT_TYPE_MB_WAY = 'MB WAY';


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'card_number',
        'balance',
        'custom',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generate_card_number()
    {
        return self::orderBy('created_at', 'desc')
            ->first()
            ?->card_number + 1 ?? 100000; // Start from 100000 if no cards exist
    }
}
