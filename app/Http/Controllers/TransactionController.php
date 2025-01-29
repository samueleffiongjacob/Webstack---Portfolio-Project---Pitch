<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        // Send money from one wallet to another
        $SuccessTransactionrequest = $request->validate([
            'sender_wallet_id' => 'required|exists:wallets,wallet_id',
            'receiver_wallet_id' => 'required|exists:wallets,wallet_id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $SuccessTransactionrequest['status_id'] = 1;

        // Retrieve sender and receiver wallets
        $wallets = Wallet::whereIn('wallet_id', [$request->sender_wallet_id, $request->receiver_wallet_id])->get();
        $sender = $wallets->where('wallet_id', $request->sender_wallet_id)->first();
        $receiver = $wallets->where('wallet_id', $request->receiver_wallet_id)->first();


        // Create the transaction
        try {
            DB::transaction(function () use ($sender, $receiver, $SuccessTransactionrequest) {

                // Lock sender's wallet for update
                $sender = Wallet::where('wallet_id', $SuccessTransactionrequest['sender_wallet_id'])->lockForUpdate()->first();
                $receiver = Wallet::where('wallet_id', $SuccessTransactionrequest['receiver_wallet_id'])->lockForUpdate()->first();

                if (!$sender || !$receiver) {
                    return response()->json(['message' => 'Sender wallet not found'], 404);
                }

                // Check if the sender has enough balance
                if ($sender->balance <  $SuccessTransactionrequest['amount']) {
                    return response()->json(['message' => 'Insufficient balance'], 402);
                }

                // Create transaction record
                Transaction::create($SuccessTransactionrequest);

            });
        } catch (\Illuminate\Database\QueryException $e) {

            // Handle SQLSTATE errors

            if ($e->getCode() === '45000') {

                // Extract error message from the exception
                $errorMessage = $e->getMessage();

                // Customize response based on trigger error message
                if (str_contains($errorMessage, 'Insufficient Funds')) {
                    return response()->json(['message' => 'Transaction failed: Insufficient Funds.',
                ], 400);
                }  elseif (str_contains($errorMessage, 'Balance cannot fall below the minimum required')) {
                    return response()->json([
                        'message' => 'Transaction failed: Insufficient Funds.',
                    ], 400);
                }

                // Default fallback for unhandled 45000 errors
                return response()->json([
                    'message' => 'Transaction failed: An error occurred while processing your request.',
                ], 400);
            }

            // General error handling
            return response()->json([
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating wallet' . $e->getMessage(),], 500);
        }

        return response()->json([
            'message' => 'Money sent successfully',
            'transaction'=> $SuccessTransactionrequest,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
