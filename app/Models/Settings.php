<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'membership_fee',
        'custom',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'membership_fee' => 'float',
        'custom' => 'array',
    ];

    public static function get(){
        return self::first() ?? null;
    }

    public static function set(float $membership_fee, array $custom)
    {
        $settings = self::first();
        if (!$settings) {
            $settings = new self();
        }

        $settings->membership_fee = $membership_fee;
        $settings->custom = $custom;

        return $settings->save();
    }

}
