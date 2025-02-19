<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tienda extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nombre',
    ];

    /**
     * Relación con el vendedor.
     * Una tienda pertenece a un solo usuario (vendedor).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los productos.
     * Una tienda puede tener muchos productos.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
