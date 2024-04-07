<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // create token
    public function login(Request $request)
    {
        if (!auth()->attempt($request->only('username', 'password'))) 
            return response()->json([
                'message' => 'Invalid login details',
            ], 401);

        $user = User::where('username', $request->username)->firstOrFail();

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'user' => [
                'name' => $user->name,
                'username' => $user->username,
            ],
            'token' => $token,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'message' => 'Register success',
            'user' => [
                'name' => $user->name,
                'username' => $user->username,
            ],
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout success',
        ]);
    }
}
