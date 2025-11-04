<?php
// app/Models/Rol.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MODELO ROL - Roles de usuario del sistema
 *
 * Define los 9 roles disponibles: Control (Admin), Informe (Jefa) y
 * las 7 áreas operativas. Cada rol determina el nivel de acceso y
 * permisos que tiene un usuario en el sistema.
 */
class Rol extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla
    protected $table = 'roles';

    protected $fillable = [
        'nombre', 'codigo', 'descripcion', 'estado'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
