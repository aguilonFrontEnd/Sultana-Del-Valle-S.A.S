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

        // Crear usuario Informe (Jefa)
        User::create([
            'name' => 'Jefa Informes',
            'email' => 'informe@sultana.com',
            'password' => Hash::make('password123'),
            'rol_id' => Rol::where('codigo', 'informe')->first()->id,
            'foto_perfil' => null,
            'estado' => true
        ]);

        // Crear usuario de área Operativo
        User::create([
            'name' => 'Usuario Operativo',
            'email' => 'operativo@sultana.com',
            'password' => Hash::make('password123'),
            'rol_id' => Rol::where('codigo', 'operativo')->first()->id,
            'foto_perfil' => null,
            'estado' => true
        ]);

        // Crear usuario de área Humanidad
        User::create([
            'name' => 'Usuario Humanidad',
            'email' => 'humanidad@sultana.com',
            'password' => Hash::make('password123'),
            'rol_id' => Rol::where('codigo', 'humanidad')->first()->id,
            'foto_perfil' => null,
            'estado' => true
        ]);
    }
}
