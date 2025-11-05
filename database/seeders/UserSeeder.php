<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario Control (Admin)
        User::create([
            'name' => 'Administrador Control',
            'email' => 'control@sultana.com',
            'password' => Hash::make('password123'),
            'rol_id' => Rol::where('codigo', 'control')->first()->id,
            'foto_perfil' => null,
            'estado' => true
        ]);

        // Crear usuario Informe
        User::create([
            'name' => 'Area Informe',
            'email' => 'informe@sultana.com',
            'password' => Hash::make('password123'),
            'rol_id' => Rol::where('codigo', 'informe')->first()->id,
            'foto_perfil' => null,
            'estado' => true
        ]);

        // Crear usuario de área Operativo
        User::create([
            'name' => 'Area Operativo',
            'email' => 'operativo@sultana.com',
            'password' => Hash::make('password123'),
            'rol_id' => Rol::where('codigo', 'operativo')->first()->id,
            'foto_perfil' => null,
            'estado' => true
        ]);

        // Crear usuario de área Humanidad
        User::create([
            'name' => 'Area Humanidad',
            'email' => 'humanidad@sultana.com',
            'password' => Hash::make('password123'),
            'rol_id' => Rol::where('codigo', 'humanidad')->first()->id,
            'foto_perfil' => null,
            'estado' => true
        ]);

        // Crear usuario de área Siniestros
        User::create([
            'name' => 'Area Siniestros',
            'email' => 'siniestros@sultana.com',
            'password' => Hash::make('password123'),
            'rol_id' => Rol::where('codigo', 'siniestros')->first()->id,
            'foto_perfil' => null,
            'estado' => true
        ]);

        // Crear usuario de área Analistas
        User::create([
            'name' => 'Area Analistas',
            'email' => 'analistas@sultana.com',
            'password' => Hash::make('password123'),
            'rol_id' => Rol::where('codigo', 'analistas')->first()->id,
            'foto_perfil' => null,
            'estado' => true
        ]);

        // Crear usuario de área Mantenimiento
        User::create([
            'name' => 'Area Mantenimiento',
            'email' => 'mantenimiento@sultana.com',
            'password' => Hash::make('password123'),
            'rol_id' => Rol::where('codigo', 'mantenimiento')->first()->id,
            'foto_perfil' => null,
            'estado' => true
        ]);

        // Crear usuario de área Documentación
        User::create([
            'name' => 'Area Documentación',
            'email' => 'documentacion@sultana.com',
            'password' => Hash::make('password123'),
            'rol_id' => Rol::where('codigo', 'documentacion')->first()->id,
            'foto_perfil' => null,
            'estado' => true
        ]);

        // Crear usuario de área Liquidación
        User::create([
            'name' => 'Area Liquidación',
            'email' => 'liquidacion@sultana.com',
            'password' => Hash::make('password123'),
            'rol_id' => Rol::where('codigo', 'liquidacion')->first()->id,
            'foto_perfil' => null,
            'estado' => true
        ]);
    }
}
