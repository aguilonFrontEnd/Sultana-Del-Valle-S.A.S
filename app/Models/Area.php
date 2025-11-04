<?php
// app/Models/Area.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MODELO AREA - Áreas operativas de la empresa
 *
 * Representa las 8 áreas de Sultana del Valle:
 * Operativo, Humanidad, Siniestros, Analistas, Mantenimiento,
 * Documentación, Liquidación y Configuración.
 * Cada área tiene usuarios asignados y permisos sobre módulos específicos.
 */
class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'codigo', 'descripcion', 'estado'
    ];

    // Relación: Un área tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relación: Un área tiene muchos permisos sobre módulos
    public function permisos()
    {
        return $this->hasMany(Permiso::class);
    }

    // Relación: Módulos a los que esta área tiene acceso
    public function modulosAccesibles()
    {
        return $this->belongsToMany(Modulo::class, 'permisos')
                    ->wherePivot('acceso', true)
                    ->withTimestamps();
    }
}
