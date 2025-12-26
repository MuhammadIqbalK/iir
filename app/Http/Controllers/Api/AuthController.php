<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle user login request
     */
 public function login(Request $request)
{
        // 1. Validasi Input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Cari User berdasarkan username (userid)
        $user = User::where('userid', $request->username)->first();

        // 3. Cek apakah user ada
        if ($user === null) {
            return response()->json([
                'message' => 'User not found.'
            ], 404); // Status 404 Not Found
        }

        // 4. Cek apakah password cocok
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Username or password is incorrect.'
            ], 401); // Status 401 Unauthorized
        }

        // 5. Buat Token
        $token = $user->createToken('auth-token')->plainTextToken;

        // 6. Return Response Sukses
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200); // Status 200 OK
    }

    /**
     * Handle user logout request
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
