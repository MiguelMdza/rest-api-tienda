<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Orden extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total',
    ];

    /**
     * Relación con el usuario.
     * Una orden pertenece a un solo usuario (cliente).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los productos comprados.
     * Una orden puede incluir muchos productos.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(OrdenProducto::class);
    }
}
