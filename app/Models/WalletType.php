<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletType extends Model
{
    //
    protected $fillable = [
        'type_name',
        'min_balance',
        'monthly_interest_rate'
    ];

    protected $casts = [
        'wallet_id' => 'integer',
        'user_id' => 'integer',
        'balance' => 'float',
        'min_balance' => 'decimal:2',
        'monthly_interest_rate' => 'decimal:2',
    ];


    public function wallets()
    {
        // return $this->hasMany(Wallet::class);
        return $this->hasMany(Wallet::class, 'wallet_type_id');
    }

    public function setTypeNameAttribute($value)
    {
        $this->attributes['type_name'] = ucfirst(trim($value)); // Capitalize and trim
    }

}
