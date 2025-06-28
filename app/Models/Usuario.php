<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nombre', 'email', 'password', 'telefono', 'tipo', 'proveedor_autenticacion', 'auth_id'];

    protected $hidden = ['password', 'remember_token'];

    // Hook para generar UUID automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($usuario) {
            if (empty($usuario->id_usuario)) {
                $usuario->id_usuario = (string) Str::uuid();
            }
        });
    }
}
