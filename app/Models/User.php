<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'tipo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relación con las tiendas.
     * Un vendedor puede tener muchas tiendas.
     */
    public function tiendas(): HasMany
    {
        return $this->hasMany(Tienda::class);
    }

    /**
     * Relación con los carritos de compra.
     * Un cliente puede tener muchos carritos.
     */
    public function carritos(): HasMany
    {
        return $this->hasMany(Carrito::class);
    }

    /**
     * Relación con las compras (historial de compras del cliente).
     * Un cliente puede haber realizado muchas compras.
     */
    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }
}
