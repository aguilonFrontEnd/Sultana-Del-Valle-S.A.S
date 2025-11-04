<?php
// database/seeders/ModuloSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Modulo;

class ModuloSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = [
            ['nombre' => 'Operativo', 'codigo' => 'operativo', 'descripcion' => 'Tablero Power BI del área operativa', 'url_powerbi' => null],
            ['nombre' => 'Humanidad', 'codigo' => 'humanidad', 'descripcion' => 'Tablero Power BI de gestión humana', 'url_powerbi' => null],
            ['nombre' => 'Siniestros', 'codigo' => 'siniestros', 'descripcion' => 'Tablero Power BI de siniestros', 'url_powerbi' => null],
            ['nombre' => 'Analistas', 'codigo' => 'analistas', 'descripcion' => 'Tablero Power BI de análisis de datos', 'url_powerbi' => null],
            ['nombre' => 'Mantenimiento', 'codigo' => 'mantenimiento', 'descripcion' => 'Tablero Power BI de mantenimiento', 'url_powerbi' => null],
            ['nombre' => 'Documentación', 'codigo' => 'documentacion', 'descripcion' => 'Tablero Power BI de documentación', 'url_powerbi' => null],
            ['nombre' => 'Liquidación', 'codigo' => 'liquidacion', 'descripcion' => 'Tablero Power BI de liquidación', 'url_powerbi' => null],
            ['nombre' => 'Configuración', 'codigo' => 'configuracion', 'descripcion' => 'Panel de control de permisos del sistema', 'url_powerbi' => null],
        ];

        foreach ($modulos as $modulo) {
            Modulo::create($modulo);
        }
    }
}
