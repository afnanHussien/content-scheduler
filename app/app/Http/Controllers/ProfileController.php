<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            "status"=> "success",
            "user" => $request->user()
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $params = $request->validate([
            "name" => "sometimes|required|string|max:255",
            "email" => "sometimes|required|email|unique:users,email," . $user->id,
        ]);

        $user->update($params);

        return response()->json([
            "status"=> "success",
            "user" => $user
        ]);
    }
}
