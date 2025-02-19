<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenProducto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'orden_id',
        'producto_id',
        'cantidad',
        'precio',
    ];

    /**
     * Relación con la orden de compra.
     */
    public function orden(): BelongsTo
    {
        return $this->belongsTo(Orden::class);
    }

    /**
     * Relación con el producto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
