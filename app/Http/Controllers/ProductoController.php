<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    // Obtener todos los productos de todas las tiendas
    public function allProductos()
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        $productos = Producto::all();

        return response()->json($productos, 200);
    }

    // Obtener todos los productos de una tienda específica
    public function index($tienda_id)
    {
        $tienda = Tienda::find($tienda_id);

        if (is_null($tienda)) {
            return response()->json(['message' => 'No se encontró la tienda'], 403);
        }

        $productos = $tienda->productos;

        if (is_null($productos)) {
            return response()->json(['message' => 'No se encontraron productos'], 403);
        }

        return response()->json($productos, 200);
    }

    // Crear un nuevo producto en una tienda específica
    public function store(Request $request, $tienda_id)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        $user = Auth::user();
        $tienda = Tienda::find($tienda_id);

        if (is_null($tienda)) {
            return response()->json(['message' => 'No se encontró la tienda'], 403);
        }

        if ($user->id != $tienda->user_id) {
            return response()->json(['message' => 'No puedes agregar productos a una tienda que no te pertenece'], 403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $producto = Producto::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'tienda_id' => $tienda->id,
        ]);

        return response()->json(['message' => 'Producto creado exitosamente', 'producto' => $producto], 201);
    }

    // Obtener un producto específico de una tienda
    public function show($tienda_id, $producto_id)
    {
        $tienda = Tienda::find($tienda_id);

        if (is_null($tienda)) {
            return response()->json(['message' => 'No se encontró la tienda'], 403);
        }

        $producto = $tienda->productos()->find($producto_id);

        if (is_null($producto)) {
            return response()->json(['message' => 'No se encontró el producto'], 403);
        }

        return response()->json($producto, 200);
    }

    // Actualizar un producto específico
    public function update(Request $request, $tienda_id, $producto_id)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        $user = Auth::user();
        $tienda = Tienda::find($tienda_id);

        if (is_null($tienda)) {
            return response()->json(['message' => 'No se encontró la tienda'], 403);
        }

        $producto = $tienda->productos()->find($producto_id);

        if (is_null($producto)) {
            return response()->json(['message' => 'No se encontró el producto'], 403);
        }

        if ($user->id != $tienda->user_id) {
            return response()->json(['message' => 'No puedes actualizar productos de una tienda que no te pertenece'], 403);
        }

        $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'precio' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
        ]);

        $producto->update($request->all());

        return response()->json(['message' => 'Producto actualizado exitosamente', 'producto' => $producto], 200);
    }

    // Eliminar un producto
    public function destroy($tienda_id, $producto_id)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        $user = Auth::user();
        $tienda = Tienda::find($tienda_id);

        if (is_null($tienda)) {
            return response()->json(['message' => 'No se encontró la tienda'], 403);
        }

        $producto = $tienda->productos()->find($producto_id);

        if (is_null($producto)) {
            return response()->json(['message' => 'No se encontró el producto'], 403);
        }

        if ($user->id != $tienda->user_id) {
            return response()->json(['message' => 'No puedes eliminar productos de una tienda que no te pertenece'], 403);
        }

        $producto->delete();

        return response()->json(['message' => 'Producto eliminado exitosamente'], 200);
    }
}
