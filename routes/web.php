<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogicSesionController;

// 🔐 AUTENTICACIÓN
Route::get('/', [LogicSesionController::class, 'showLoginForm'])
    ->name('login.form');

Route::post('/login', [LogicSesionController::class, 'login'])
    ->name('login');

Route::post('/logout', [LogicSesionController::class, 'logout'])
    ->name('logout');

// 🔄 VISTA DE CARGA LUEGO DEL LOGIN
Route::get('/view-load', [LogicSesionController::class, 'viewLoad'])
    ->name('view.load')
    ->middleware('auth');

// 🧩 VISTA DE MÓDULOS PRINCIPALES
Route::get('/modules', [LogicSesionController::class, 'redirectToModules'])
    ->name('modules')
    ->middleware('auth');

// ⚙️ VISTA DE CONFIGURACIÓN (solo rol informe desde el front)
Route::get('/config', [LogicSesionController::class, 'showConfig'])
    ->name('config')
    ->middleware('auth');

// 📘 VISTA TUTORIAL ANTES DEL TABLERO (roles ≠ informe)
Route::get('/tablero/{modulo}/tutorial', [LogicSesionController::class, 'showTutorial'])
    ->name('tablero.tutorial')
    ->middleware('auth');

// 📊 TABLERO POWER BI
Route::get('/tablero/{modulo}', [LogicSesionController::class, 'showTablero'])
    ->name('tablero.show')
    ->middleware('auth');
