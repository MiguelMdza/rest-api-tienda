<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    // Obtener el carrito del usuario autenticado
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        $user = Auth::user();
        $carrito = Carrito::where('user_id', $user->id)->with('productos')->first();

        if (is_null($carrito)) {
            return response()->json(['message' => 'No se encontró el carrito'], 404);
        }

        return response()->json([
            'message' => 'Carrito obtenido exitosamente',
            'carrito' => $carrito,
            'productos_del_carrito' => $carrito->productos,
        ]);
    }

    // Agregar producto al carrito
    public function agregarProducto(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        if ($user->tipo !== "Cliente") {
            return response()->json(['message' => 'Solo los clientes pueden crear carritos'], 403);
        }

        $producto = Producto::find($request->producto_id);

        if (is_null($producto)) {
            return response()->json(['message' => 'No se encontró el producto'], 404);
        }

        if ($producto->stock < $request->cantidad) {
            return response()->json(['message' => 'Stock insuficiente'], 400);
        }

        // Buscar o crear el carrito del usuario
        $carrito = Carrito::firstOrCreate(['user_id' => $user->id]);

        // Buscar si el producto ya está en el carrito
        $carritoProducto = $carrito->productos()->where('producto_id', $producto->id)->first();

        if ($carritoProducto) {
            // Si ya existe, actualizar la cantidad
            $carritoProducto->pivot->cantidad += $request->cantidad;
            $carritoProducto->pivot->save();
        } else {
            // Si no existe, agregarlo a la tabla intermedia
            $carrito->productos()->attach($producto->id, ['cantidad' => $request->cantidad]);
        }

        return response()->json(['message' => 'Producto agregado al carrito', 'carrito' => $carrito], 201);
    }

    // Remover un producto del carrito
    public function removerProducto($producto_id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        $user = Auth::user();

        if ($user->tipo !== "Cliente") {
            return response()->json(['message' => 'Solo los clientes pueden remover producto de los carritos'], 403);
        }

        $carrito = Carrito::where('user_id', $user->id)->first();

        if (is_null($carrito)) {
            return response()->json(['message' => 'No tienes un carrito activo'], 404);
        }

        // Buscar si el producto está en el carrito
        if (!$carrito->productos()->where('producto_id', $producto_id)->exists()) {
            return response()->json(['message' => 'No se puede remover un producto que no está en el carrito'], 404);
        }

        $carrito->productos()->detach($producto_id);

        return response()->json(['message' => 'Producto eliminado del carrito exitosamente'], 200);
    }

    // Vaciar el carrito de compras
    public function vaciarCarrito()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        $user = Auth::user();

        if ($user->tipo !== "Cliente") {
            return response()->json(['message' => 'Solo los clientes pueden vaciar carritos'], 403);
        }

        $carrito = Carrito::where('user_id', $user->id)->first();

        if (is_null($carrito)) {
            return response()->json(['message' => 'No tienes un carrito activo'], 404);
        }

        $carrito->productos()->detach();

        return response()->json(['message' => 'Carrito vaciado exitosamente'], 200);
    }
}
