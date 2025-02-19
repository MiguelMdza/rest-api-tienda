<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'tipo' => 'required|in:Cliente,Vendedor',
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tipo' => $request->tipo,
        ]);

        return response()->json(['message' => 'Usuario creado exitosamente', 'user' => $user], 201);
    }

    // Login de usuario
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            return response()->json(['message' => 'Login exitoso', 'user' => $user, 'token' => $user->createToken('API Tienda')->plainTextToken]);
        }

        return response()->json(['message' => 'Sin autorización'], 401);
    }

    // Logout de usuario
    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logged out exitoso']);
    }
}
