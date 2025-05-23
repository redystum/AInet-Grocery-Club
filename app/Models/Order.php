<?php

namespace App\Models;

use App\Utils\CustomFieldManager;
use Illuminate\Database\Eloquent\Model;


/**
 * @property CustomFieldManager $customManager
 *
 * @property string $cancellationStatus
 * @property string $cancellationTime
 */
class Order extends Model
{
    const STATUS_COMPLETED = "completed";
    const STATUS_PENDING = "pending";
    const STATUS_CANCELED = "canceled";

    const CANCEL_STATUS_PENDING = "pending";
    const CANCEL_STATUS_REFUSED = "refused";
    const CANCEL_STATUS_ACCEPTED = "accepted";

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'member_id',
        'status',
        'date',
        'total_items',
        'shipping_cost',
        'total',
        'nif',
        'delivery_address',
        'pdf_receipt',
        'cancel_reason',
        'custom',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function items()
    {
        return $this->hasMany(ItemsOrder::class);
    }

    public function products()
    {
        return $this->hasMany(ItemsOrder::class)->with('product');
    }

}
