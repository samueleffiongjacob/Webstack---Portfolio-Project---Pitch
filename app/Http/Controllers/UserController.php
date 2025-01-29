<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): JsonResponse
    {
        //
        $users = User::all();
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }
        return response()->json($users, 200);
    }

    /**
     * Store a newly created created user  in storage.
     */
    public function store(Request $request): JsonResponse
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);


        // Check if the user with the same name and email already exists
        $existingUser = User::where('email', $request->email)
                            ->where('name', $request->name)
                            ->first();

        if ($existingUser) {
            return response()->json([
                'message' =>
                    "{$request->name}
                    with email
                    {$request->email}
                    already exists."
            ], 409);
        }

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
                                'message' =>
                                'New User created successfully',
                                'user'
                                => $user], 201);
    }

    /**
     * Display the specified user.
     */

    // return id if not work
    public function show(string $id): JsonResponse
    {
        //
        $user = User::with('wallets.walletType')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User with ID {$id} not found'], 404);
        }

        // Check if the user has any wallets
        if ($user->wallets->isEmpty()) {
            return response()->json(['message' => 'No wallets created for this user'], 404);
        }

        return response()->json($user->wallets, 200);// can remove ->wallets for seeing only users
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        //
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update($request->all());

        return response()->json($user, 200);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        //
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted Succesfully'], 200);
    }
}
