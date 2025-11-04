<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Rol;

/**
 * MODELO USER - Usuarios del sistema
 *
 * Gestiona todos los usuarios que pueden acceder al sistema.
 * Cada usuario tiene un rol que define sus permisos (Control, Informe o Área específica).
 * Se relaciona con el modelo Rol para determinar qué puede ver y hacer en el sistema.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'rol_id', 'foto_perfil', 'estado'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación: Un usuario pertenece a un rol
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    // Método: Verifica si el usuario es Rol Control (Administrador)
    public function isControl()
    {
        return $this->rol->codigo === 'control';
    }

    // Método: Verifica si el usuario es Rol Informe (Solo lectura)
    public function isInforme()
    {
        return $this->rol->codigo === 'informe';
    }

    // Método: Verifica si el usuario es de un área específica
    public function isArea()
    {
        return $this->rol->codigo !== 'control' && $this->rol->codigo !== 'informe';
    }
}
