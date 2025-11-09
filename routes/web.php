<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SesionController;

// Rutas de autenticación
Route::get('/', [SesionController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [SesionController::class, 'login'])->name('login');
Route::get('/view-load', [SesionController::class, 'viewLoad'])->name('view.load')->middleware('auth');
Route::get('/modules', [SesionController::class, 'redirectToModules'])->name('modules')->middleware('auth');
Route::post('/logout', [SesionController::class, 'logout'])->name('logout');

// Ruta para actualizar foto de perfil
Route::put('/profile/photo', [SesionController::class, 'updatePhoto'])->name('profile.update-photo');

// Ruta para tableros Power BI
Route::get('/tablero/{modulo}', [SesionController::class, 'showTablero'])->name('tablero.show')->middleware('auth');