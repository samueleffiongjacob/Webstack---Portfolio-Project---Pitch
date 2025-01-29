<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionStatus extends Model
{
    //
    use HasFactory;
    // If your table name is different from the pluralized model name, define it explicitly
    protected $table = 'transaction_statuses';

    // Set the primary key (if it is not `id`)
    protected $primaryKey = 'status_id';

    // Allow mass assignment for specific columns
    protected $fillable = ['status_name'];

    /**
     * Define the relationship between TransactionStatus and Transaction.
     * A TransactionStatus can belong to many Transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'status_id');
    }
}
