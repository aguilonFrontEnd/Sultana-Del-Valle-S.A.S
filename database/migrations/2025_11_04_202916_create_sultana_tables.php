// database/migrations/2024_01_01_000001_create_sultana_tables.php
// Base de datos del sistema directo Sultana del Valle S.A.S

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabla de áreas
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        // Tabla de módulos
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->text('descripcion')->nullable();
            $table->string('url_powerbi')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        // Tabla de roles (los 9 roles)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // control, informe, operativo, humanidad, etc.
            $table->string('codigo')->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        // Tabla de permisos
        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas');
            $table->foreignId('modulo_id')->constrained('modulos');
            $table->boolean('acceso')->default(false);
            $table->timestamps();

            $table->unique(['area_id', 'modulo_id']);
        });

        // Extender usuarios con rol_id
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('rol_id')->constrained('roles');
            $table->string('foto_perfil')->nullable();
            $table->boolean('estado')->default(true);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rol_id']);
            $table->dropColumn(['rol_id', 'foto_perfil', 'estado']);
        });

        Schema::dropIfExists('permisos');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('modulos');
        Schema::dropIfExists('areas');
    }
};
