<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tienda_id',
        'nombre',
        'precio',
        'stock',
    ];

    /**
     * Relación con la tienda.
     * Un producto pertenece a una sola tienda.
     */
    public function tiendas(): BelongsTo
    {
        return $this->belongsTo(Tienda::class);
    }

    /**
     * Relación con los carritos de compra.
     * Un producto puede estar en muchos carritos.
     */
    public function carritos(): HasMany
    {
        return $this->hasMany(CarritoProducto::class);
    }

    /**
     * Relación con las órdenes de compra.
     * Un producto puede haber sido comprado en muchas órdenes.
     */
    public function ordenes(): HasMany
    {
        return $this->hasMany(OrdenProducto::class);
    }
}
