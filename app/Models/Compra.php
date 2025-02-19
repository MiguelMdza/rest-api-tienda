<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compra extends Model
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
     * Una compra pertenece a un solo usuario (cliente).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los productos comprados.
     * Una compra puede incluir muchos productos.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(CompraProducto::class);
    }
}
