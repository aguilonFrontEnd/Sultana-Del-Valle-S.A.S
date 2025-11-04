<?php
// database/seeders/RolSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Control', 'codigo' => 'control', 'descripcion' => 'Administrador del sistema - Acceso total'],
            ['nombre' => 'Informe', 'codigo' => 'informe', 'descripcion' => 'Rol de solo lectura - Ve todos los módulos'],
            ['nombre' => 'Operativo', 'codigo' => 'operativo', 'descripcion' => 'Área operativa de transportes'],
            ['nombre' => 'Humanidad', 'codigo' => 'humanidad', 'descripcion' => 'Área de gestión humana'],
            ['nombre' => 'Siniestros', 'codigo' => 'siniestros', 'descripcion' => 'Área de gestión de siniestros'],
            ['nombre' => 'Analistas', 'codigo' => 'analistas', 'descripcion' => 'Área de análisis de datos'],
            ['nombre' => 'Mantenimiento', 'codigo' => 'mantenimiento', 'descripcion' => 'Área de mantenimiento vehicular'],
            ['nombre' => 'Documentación', 'codigo' => 'documentacion', 'descripcion' => 'Área de documentación y archivo'],
            ['nombre' => 'Liquidación', 'codigo' => 'liquidacion', 'descripcion' => 'Área de liquidación de pagos'],
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}
