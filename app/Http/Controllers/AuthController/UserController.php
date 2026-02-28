<?php

namespace App\Http\Controllers\AuthController;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class UserController extends Controller
{
public function register(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:50'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => [
            'required',
            'confirmed',
            Password::min(8)->letters()->numbers()
        ],
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password'])
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Registration successful. Please login.'
    ], 201);
}


public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        // Removed session regeneration since this is a stateless API route without session middleware
        return response()->json([
            'status' => true
        ]);
    }

    return response()->json([
        'status' => false,
        'message' => 'Invalid credentials'
    ], 401);
}




}
