<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SesionController;
use App\Models\User;
use App\Models\Rol;

// Rutas de autenticación
Route::get('/', [SesionController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [SesionController::class, 'login'])->name('login');
Route::get('/view-load', [SesionController::class, 'viewLoad'])->name('view.load')->middleware('auth');
Route::get('/modules', [SesionController::class, 'redirectToModules'])->name('modules')->middleware('auth');
Route::post('/logout', [SesionController::class, 'logout'])->name('logout');

// Ruta para actualizar foto de perfil
Route::put('/profile/photo', [SesionController::class, 'updatePhoto'])->name('profile.update-photo');

// Rutas de verificación (para AJAX)
Route::get('/check-auth', [SesionController::class, 'checkAuth'])->name('check.auth');

// Ruta para tableros Power BI
Route::get('/tablero/{modulo}', function ($modulo) {
    // Verificar autenticación
    if (!Auth::check()) {
        return redirect()->route('login.form')->with('toast', [
            'type' => 'error',
            'message' => 'Debes iniciar sesión primero.'
        ]);
    }

    $user = Auth::user();

    // Cargar la relación rol de forma segura
    $userWithRol = User::with('rol')->find($user->id);

    $moduloNombres = [
        'operativo' => 'Operativo',
        'humanidad' => 'Humanidad',
        'siniestros' => 'Siniestros',
        'analistas' => 'Analistas',
        'mantenimiento' => 'Mantenimiento',
        'documentacion' => 'Documentación',
        'liquidacion' => 'Liquidación',
        'configuracion' => 'Configuración'
    ];

    return view('Table.view-tables', [ // 👈 RUTA CORRECTA: Table/view-tables
        'user' => $userWithRol,
        'userName' => $userWithRol->name,
        'userArea' => $userWithRol->rol->nombre,
        'userRol' => $userWithRol->rol->codigo,
        'moduloNombre' => $moduloNombres[$modulo] ?? 'General',
        'powerbiUrl' => '#' // Placeholder para Power BI
    ]);
})->name('tablero.show')->middleware('auth'); // 👈 AGREGAR MIDDLEWARE AUTH
