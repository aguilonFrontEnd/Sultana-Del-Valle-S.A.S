<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogicSesionController;

// Rutas de autenticación
Route::get('/', [LogicSesionController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LogicSesionController::class, 'login'])->name('login');
Route::get('/view-load', [LogicSesionController::class, 'viewLoad'])->name('view.load')->middleware('auth');
Route::get('/modules', [LogicSesionController::class, 'redirectToModules'])->name('modules')->middleware('auth');
Route::post('/logout', [LogicSesionController::class, 'logout'])->name('logout');
Route::put('/profile/photo', [LogicSesionController::class, 'updatePhoto'])->name('profile.update-photo');
Route::get('/tablero/{modulo}', [LogicSesionController::class, 'showTablero'])->name('tablero.show')->middleware('auth');