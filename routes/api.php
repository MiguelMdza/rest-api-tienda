<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CompraController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas de autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Rutas protegidas con usuario autenticado
Route::middleware('auth:sanctum')->group(function () {

    // Información del usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas para crud tiendas
    Route::apiResource('tiendas', TiendaController::class);

    // Rutas para crud productos
    Route::apiResource('tiendas.{tienda}/productos', ProductoController::class);
    Route::get('/ver-productos', [ProductoController::class, 'allProductos']); // Para ver todos los productos

    // Rutas para el carrito de compras
    Route::get('/carrito', [CarritoController::class, 'index']);
    Route::post('/carrito/agregar', [CarritoController::class, 'agregarProducto']);
    Route::delete('/carrito/remover/{producto}', [CarritoController::class, 'removerProducto']);
    Route::delete('/carrito/vaciar', [CarritoController::class, 'vaciarCarrito']);

    // Rutas para compras
    Route::get('/compras', [CompraController::class, 'index']);
    Route::post('/compras', [CompraController::class, 'store']);
    Route::get('/compras/{compra}', [CompraController::class, 'show']);
});
