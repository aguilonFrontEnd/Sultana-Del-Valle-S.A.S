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
            ['nombre' => 'Control', 'codigo' => 'control'],
            ['nombre' => 'Informe', 'codigo' => 'informe'],
            ['nombre' => 'Operativo', 'codigo' => 'operativo'],
            ['nombre' => 'Humanidad', 'codigo' => 'humanidad'],
            ['nombre' => 'Siniestros', 'codigo' => 'siniestros'],
            ['nombre' => 'Analistas', 'codigo' => 'analistas'],
            ['nombre' => 'Mantenimiento', 'codigo' => 'mantenimiento'],
            ['nombre' => 'Documentación', 'codigo' => 'documentacion'],
            ['nombre' => 'Liquidación', 'codigo' => 'liquidacion'],
            // NUEVOS ROLES
            ['nombre' => 'Contadora', 'codigo' => 'contadora'],
            ['nombre' => 'Cartera', 'codigo' => 'cartera'],
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}
