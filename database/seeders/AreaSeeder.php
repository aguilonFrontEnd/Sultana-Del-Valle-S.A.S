<?php
// database/seeders/AreaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['nombre' => 'Operativo', 'codigo' => 'operativo', 'descripcion' => 'Área operativa de transportes y logística'],
            ['nombre' => 'Humanidad', 'codigo' => 'humanidad', 'descripcion' => 'Área de gestión humana y talento'],
            ['nombre' => 'Siniestros', 'codigo' => 'siniestros', 'descripcion' => 'Área de gestión y control de siniestros'],
            ['nombre' => 'Analistas', 'codigo' => 'analistas', 'descripcion' => 'Área de análisis de datos y business intelligence'],
            ['nombre' => 'Mantenimiento', 'codigo' => 'mantenimiento', 'descripcion' => 'Área de mantenimiento vehicular y flota'],
            ['nombre' => 'Documentación', 'codigo' => 'documentacion', 'descripcion' => 'Área de documentación y gestión de archivos'],
            ['nombre' => 'Liquidación', 'codigo' => 'liquidacion', 'descripcion' => 'Área de liquidación de pagos y nómina'],
            ['nombre' => 'Configuración', 'codigo' => 'configuracion', 'descripcion' => 'Área de configuración del sistema'],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
