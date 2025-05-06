<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\AccountActivation;
use App\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'blocked',
        'gender',
        'photo',
        'nif',
        'default_delivery_address',
        'default_payment_type',
        'default_payment_reference',
        'custom',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function sendEmailVerificationNotification()
    {
        $verificationUrl = URL::temporarySignedRoute(
            'activation',
            now()->addMinutes(60),
            ['id' => $this->id, 'hash' => sha1($this->email)]
        );

        $this->notify(new AccountActivation($verificationUrl));
    }

    public function isEmployee(): bool
    {
        return $this->type === 'employee';
    }

    public function isBoard(): bool
    {
        return $this->type === 'board';
    }

    public function isMember(): bool
    {
        return $this->type === 'member';
    }

    public function card()
    {
        return $this->hasOne(Card::class, 'id', 'id');
    }

    public function lastOrders()
    {
        return $this->hasMany(Order::class, 'member_id', 'id')->with('items')->latest()->take(5);
    }
}
