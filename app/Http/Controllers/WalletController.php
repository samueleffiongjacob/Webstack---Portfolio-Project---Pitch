<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\User;
use App\Models\WalletType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class WalletController extends Controller
{
    public function getAllWallets(): JsonResponse
    {
        // Fetch all wallets
        $wallets = Wallet::all();

        // Return response
        return response()->json([
            'message' => 'All wallets retrieved successfully.',
            'wallets' => $wallets,
        ], 200);
    }

    /**
     *  Get all wallets with types and users
     */
    public function index(): JsonResponse
    {
        //
        $wallets = Wallet::with(['user', 'walletType'])->get();

        if ($wallets->isEmpty()) {
            return response()->json(['message' => 'No wallets found'], 404);
        }

        return response()->json($wallets, 200);
    }

    /**
     * Store a newly created wallet  in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'name' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'wallet_type_id' => 'required|exists:wallet_types,wallet_type_id',
            'wallet_type' => 'required|string|max:255',
            'min_balance' => 'nullable|numeric|min:0',
            'monthly_interest_rate' => 'nullable|numeric|min:0|max:100', // Add validation for interest rate
            'balance' => 'required|numeric|min:0',
        ]);


        // Validate the user
        $user = User::validateUser($request->user_id, $request->email);

        if ($user instanceof JsonResponse) {
            return $user; // If validation failed, return the response
        }
        // Ensure wallet_type is trimmed
        $walletTypeName = trim($request->wallet_type);


        $walletType = WalletType::firstOrCreate(
            ['type_name' => $walletTypeName],
            [
                'min_balance' => $request->min_balance ?? 0.00,
                'monthly_interest_rate' => $request->monthly_interest_rate ?? 0.00,
            ]
        );

        // Validate the wallet
        $walletCheck = Wallet::validateUniqueWallet($request->user_id, $request->wallet_type_id, $request->wallet_type);
        if ($walletCheck instanceof JsonResponse) {
            return $walletCheck;
        }
        try {
            $wallet = Wallet::create($validated);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating wallet'], 500);
        }

        // respose
        return response()->json([
            'message' => 'Wallet created successfully',
            'wallet' => $wallet,
            'wallet_type' => $walletType,
        ], 201);
    }

    /**
     * Get a wallet's details.
     */
    public function show(string $id): JsonResponse
    {


        // Find the user by ID (and their wallets)
        $user = User::with('wallets.walletType')->find($id);


        // If the user is not found, return a 404 response
        if (!$user) {
            return response()->json(['message' => 'Specified User {$id} with Wallets with ID {$id} not found'], 404);
        }


        //  return only the wallets related to the user
        return response()->json($user->wallets, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        //
        $wallet = Wallet::find($id);

        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $request->validate([
            'balance' => 'nullable|numeric|min:0',
        ]);

        $wallet->update($request->all());

        return response()->json($wallet, 200);
    }

    /**
     * Remove the specified wallet from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        //
        $wallet = Wallet::find($id);

        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $wallet->delete();

        return response()->json(['message' => 'Wallet deleted'], 200);
    }
}
