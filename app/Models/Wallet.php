<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    // protected $table = 'wallet_types';
    // Explicitly set the primary key
    // protected $primaryKey = 'wallet_type_id';
    protected $primaryKey = 'wallet_id';

    // Specify that the key is auto-incrementing and numeric
    public $incrementing = true;
    protected $keyType = 'int';

    //
    protected $fillable = [
        'user_id',
        'wallet_type_id',
        'wallet_type',
        'name',
        'email',
        'min_balance',
        'balance',
        'monthly_interest_rate',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
        // return $this->belongsTo(User::class);
    }

    public static function validateUniqueWallet($userId, $walletTypeId, $walletType)
    {

        // Check if the user exists
        $userExists = User::find($userId); // Assuming 'User' is the model for users
        if (!$userExists) {
            return response()->json([
                'message' => "User does not exist. Wallet cannot be created."
            ], 402); // HTTP 404 Not Found
        }

        // Check if the wallet type exists


        $existingWallet = self::where('user_id', $userId)
            ->where('wallet_type_id', $walletTypeId)
            ->where('wallet_type', $walletType)
            ->first();

        if ($existingWallet) {
            return response()->json([
                'message' => "You already have this wallet: {$walletType}"
            ], 409);
        }

        // Check if the user already has all four types of wallets
        $walletCount = self::where('user_id', $userId)->distinct('wallet_type_id')->count();

        if ($walletCount >= 4) {
            return response()->json([
                'message' => "You already have all 4 wallet types. You cannot create additional wallets."
            ], 409);
        }

        return null; // Validation passed
    }


    public function walletType()
    {
        return $this->belongsTo(WalletType::class, 'wallet_type_id', 'wallet_type_id');
        // return $this->belongsTo(WalletType::class);
    }

    public function sentTransactions()
    {
        return $this->hasMany(Transaction::class, 'sender_wallet_id', 'sender_wallet_id');
    }

    public function receivedTransactions()
    {
        return $this->hasMany(Transaction::class, 'receiver_wallet_id', 'receiver_wallet_id');
    }
}
