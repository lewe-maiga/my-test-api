<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware(["auth:api"], ["except" => ["login", "register"]]);
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|string|email",
            "password" => "required|string|min:6|max:16",
        ]);

        $credentials = $request->only("email", "password");
        $token = auth()->attempt($credentials);


        if (!$token) {
            return response()->json([
                "status" => "error",
                "message" => "Email/Password Incorrect"
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            "user" => $user,
            "authorization" => [
                "token" => $token,
                "type" => "bearer",
                "expires_in" => JWTAuth::factory()->getTTL() * 60,
            ],
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            "message" => "Successfully logged out"
        ]);

    }

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:16',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ]);
    }

    public function refresh()
    {
        return response()->json([
            "user" => auth()->user(),
            "authorization" => [
                "token" => Auth::refresh(),
                "type" => "bearer",
            ],
        ]);
    }

    public function me()
    {
        return response()->json(
            auth()->user()
        );

    }
}