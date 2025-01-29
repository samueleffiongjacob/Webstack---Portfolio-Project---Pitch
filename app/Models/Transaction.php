<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Transaction extends Model
{
    use HasFactory;
    protected $primaryKey = 'transaction_id'; // Custom primary key
    //
    protected $fillable = [
        'sender_wallet_id',
        'receiver_wallet_id',
        'amount',
        'status_id'
    ];

    protected $casts = [
        'wallet_id' => 'integer',
        'user_id' => 'integer',
        'balance' => 'decimal',
    ];
    public function senderWallet()
    {
        return $this->belongsTo(Wallet::class, 'sender_wallet_id', 'sender_wallet_id');
    }

    public function receiverWallet()
    {
        return $this->belongsTo(Wallet::class, 'receiver_wallet_id', 'receiver_wallet_id');
    }



    public function status()
    {
        return $this->belongsTo(TransactionStatus::class, 'status_id', 'status_id');
    }
}
