<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarritoProducto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'carrito_id',
        'producto_id',
        'cantidad',
    ];

    /**
     * Relación con el carrito.
     */
    public function carrito(): BelongsTo
    {
        return $this->belongsTo(Carrito::class);
    }

    /**
     * Relación con el producto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
