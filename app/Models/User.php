<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'ssn',
        'password',
        'ip',
        'last_login',
        'user_agent',
    ];


    public function wallets()
    {
        return $this->hasMany(Wallet::class, 'user_id');
        // return $this->hasMany(Wallet::class);
    }

    //  User Validation helper to be use controller
    public static function validateUser($userId, $email)
    {
        $user = self::where('user_id', $userId)
                    ->where('email', $email)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'User with ID {$request->user_id} not found'], 404);
        }

        return $user;
    }
    
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
}
