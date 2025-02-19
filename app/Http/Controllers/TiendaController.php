<?php

namespace App\Http\Controllers;

use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TiendaController extends Controller
{
    // Obtener todas las tiendas
    public function index()
    {
        $tiendas = Tienda::all();

        return response()->json($tiendas);
    }

    // Crear una tienda
    public function store(Request $request)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        $user = Auth::user();

        if ($user->tipo !== "Vendedor") {
            return response()->json(['message' => 'Solo los vendedores pueden crear tiendas'], 403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tienda = Tienda::create([
            'nombre' => $request->nombre,
            'user_id' => Auth::user()->id,
        ]);

        return response()->json(['message' => 'Tienda creada exitosamente', 'tienda' => $tienda], 201);
    }

    // Obtener una tienda específica
    public function show($id)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        $tienda = Tienda::find($id);

        if (is_null($tienda)) {
            return response()->json(['message' => 'No se encontró la tienda'], 403);
        }

        return response()->json($tienda);
    }

    // Actualizar una tienda
    public function update(Request $request, $id)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        $user = Auth::user();
        $tienda = Tienda::find($id);

        if (is_null($tienda)) {
            return response()->json(['message' => 'No se encontró la tienda'], 403);
        }

        if ($user->id != $tienda->user_id) {
            return response()->json(['message' => 'No puedes actualizar tiendas que no te pertenecen'], 403);
        }

        $tienda->update($request->all());

        return response()->json(['message' => 'Tienda actualizada exitosamente', 'tienda' => $tienda], 201);
    }

    // Eliminar una tienda
    public function destroy($id)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        $user = Auth::user();
        $tienda = Tienda::find($id);

        if (is_null($tienda)) {
            return response()->json(['message' => 'No se encontró la tienda'], 403);
        }

        if ($user->id != $tienda->user_id) {
            return response()->json(['message' => 'No puedes eliminar tiendas que no te pertenecen'], 403);
        }

        $tienda->delete();
        // Respuesta:
        // { "message": "Tienda deleted successfully" }
        return response()->json(['message' => 'Tienda eliminada exitosamente'], 201);
    }
}
