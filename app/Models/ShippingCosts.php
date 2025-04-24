<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCosts extends Model
{
    protected $table = 'settings_shipping_costs';

    protected $fillable = [
        'min_value_threshold',
        'max_value_threshold',
        'shipping_cost',
        'custom'
    ];

    // alias for min_value_threshold, usage: $shipping_cost->min
    public function getMinAttribute()
    {
        return $this->min_value_threshold;
    }

    // alias for max_value_threshold, usage: $shipping_cost->max
    public function getMaxAttribute()
    {
        return $this->max_value_threshold;
    }

    // alias for shipping_cost, usage: $shipping_cost->cost
    public function getCostAttribute()
    {
        return $this->shipping_cost;
    }


}
