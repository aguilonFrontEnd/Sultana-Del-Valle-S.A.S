<?php
// app/Models/Modulo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MODELO MODULO - Módulos del sistema
 *
 * Representa los 8 módulos/tableros Power BI del sistema:
 * Cada módulo corresponde a un área y contiene la URL del tablero Power BI.
 * Los módulos pueden estar activos o bloqueados para cada área según los permisos.
 */
class Modulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'codigo', 'descripcion', 'url_powerbi', 'estado'
    ];

    // Relación: Un módulo tiene muchos permisos
    public function permisos()
    {
        return $this->hasMany(Permiso::class);
    }

    // Relación: Áreas que tienen acceso a este módulo
    public function areasConAcceso()
    {
        return $this->belongsToMany(Area::class, 'permisos')
                    ->wherePivot('acceso', true)
                    ->withTimestamps();
    }
}
