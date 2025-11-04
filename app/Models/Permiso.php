<?php
// app/Models/Permiso.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MODELO PERMISO - Permisos de acceso a módulos
 *
 * Tabla pivote que controla qué áreas pueden acceder a qué módulos.
 * El campo 'acceso' determina si un área específica puede ver y usar un módulo.
 * Es la base del sistema de permisos visuales del sistema.
 */
class Permiso extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id', 'modulo_id', 'acceso'
    ];

    // Relación: Un permiso pertenece a un área
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // Relación: Un permiso pertenece a un módulo
    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}
