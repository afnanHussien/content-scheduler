<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $params = $request->validate([
            "email"=> "required|string|email|unique:users,email",
            "password"=> "required|string|min:6|max:50|confirmed",
            "name"=> "required|string|min:3|max:255"
        ]);
        $user = User::create([
            "email"=> $params["email"],
            "password"=> bcrypt($params["password"]),
            "name"=> $params["name"],
        ]);
        Auth::login($user);
        $request->session()->regenerate();
        return response()->json(['message' => 'Registered successfully'], 200);
    }

    public function login(Request $request)
    {
        $params = $request->validate([
            "email"=> "required|string|email",
            "password"=> "required|string"
        ]);
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Login successful'], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
