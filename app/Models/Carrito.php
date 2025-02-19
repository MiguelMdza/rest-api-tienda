<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrito extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * Relación con el usuario.
     * Un carrito pertenece a un solo usuario (cliente).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los productos en el carrito.
     * Un carrito puede tener muchos productos.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(CarritoProducto::class);
    }
}
