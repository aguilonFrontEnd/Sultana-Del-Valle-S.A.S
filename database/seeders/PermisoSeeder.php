<?php
// database/seeders/PermisoSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permiso;
use App\Models\Area;
use App\Models\Modulo;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        $areas = Area::all();
        $modulos = Modulo::all();

        // Por defecto, cada área solo tiene acceso a su módulo correspondiente
        foreach ($areas as $area) {
            foreach ($modulos as $modulo) {
                $acceso = ($area->codigo === $modulo->codigo);
                Permiso::create([
                    'area_id' => $area->id,
                    'modulo_id' => $modulo->id,
                    'acceso' => $acceso
                ]);
            }
        }
    }
}
