<?php
// app/Models/Rol.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    // ✅ ESPECIFICAR EL NOMBRE DE LA TABLA EXPLÍCITAMENTE
    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'estado'
    ];

    // Relación: Un rol tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Método: Verifica si es rol Control (Administrador)
    public function isControl()
    {
        return $this->codigo === 'control';
    }

    // Método: Verifica si es rol Informe (Solo lectura)
    public function isInforme()
    {
        return $this->codigo === 'informe';
    }

    // Método: Verifica si es rol de área específica
    public function isArea()
    {
        return !$this->isControl() && !$this->isInforme();
    }

    // Método: Obtener rol por código
    public static function findByCodigo($codigo)
    {
        return self::where('codigo', $codigo)->first();
    }
}
