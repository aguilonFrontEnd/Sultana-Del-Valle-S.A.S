<?php
// app/Http/Controllers/SesionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Rol;

class SesionController extends Controller
{
    /**
     * MOSTRAR FORMULARIO DE LOGIN
     */
    public function showLoginForm()
    {
        return view('Form.view-form');
    }

    /**
     * PROCESAR INICIO DE SESIÓN
     */
    public function login(Request $request)
    {
        try {
            // Validar campos obligatorios
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);

            // Buscar usuario por email CON la relación rol
            $user = User::with('rol')->where('email', $request->email)->first();

            // Verificar si el usuario existe
            if (!$user) {
                return redirect()->back()->with('toast', [
                    'type' => 'error',
                    'message' => 'El usuario no existe en el sistema.'
                ])->withInput();
            }

            // Verificar si el usuario está activo
            if (!$user->estado) {
                return redirect()->back()->with('toast', [
                    'type' => 'error',
                    'message' => 'Tu cuenta está desactivada. Contacta al administrador.'
                ])->withInput();
            }

            // Verificar contraseña
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->back()->with('toast', [
                    'type' => 'error',
                    'message' => 'La contraseña es incorrecta.'
                ])->withInput();
            }

            // Iniciar sesión
            Auth::login($user);
            $request->session()->regenerate();

            // Redirigir a view-load
            return redirect()->route('view.load')->with('toast', [
                'type' => 'success',
                'message' => '¡Bienvenido ' . $user->name . '!'
            ]);

        } catch (\Exception $e) {
            // Error inesperado
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Error al iniciar sesión. Intenta nuevamente.'
            ])->withInput();
        }
    }

    /**
     * VISTA DE CARGA CON REDIRECCIÓN AUTOMÁTICA A MÓDULOS
     */
    public function viewLoad()
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('toast', [
                'type' => 'error',
                'message' => 'Debes iniciar sesión primero.'
            ]);
        }

        $user = Auth::user();

        // Cargar la relación del rol usando with()
        $userWithRol = User::with('rol')->find($user->id);

        return view('view-load', [
            'userName' => $userWithRol->name,
            'userArea' => $userWithRol->rol->nombre,
            'userRol' => $userWithRol->rol->codigo
        ]);
    }

    /**
     * REDIRIGIR A LA VISTA DE MÓDULOS (Modules/view-modules.blade.php)
     */
    public function redirectToModules()
    {
        $user = Auth::user();

        // Verificar autenticación
        if (!$user) {
            return redirect()->route('login.form')->with('toast', [
                'type' => 'error',
                'message' => 'Debes iniciar sesión primero.'
            ]);
        }

        // Cargar la relación del rol
        $userWithRol = User::with('rol')->find($user->id);

        // Redirigir a la vista de módulos en la carpeta Modules
        return view('Modules.view-modules', [
            'user' => $userWithRol,
            'userName' => $userWithRol->name,
            'userRol' => $userWithRol->rol->codigo,
            'userArea' => $userWithRol->rol->nombre
        ]);
    }

    /**
     * ACTUALIZAR FOTO DE PERFIL
     */
    public function updatePhoto(Request $request)
    {
        try {
            $request->validate([
                'foto_perfil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $user = Auth::user();

            // Eliminar foto anterior si existe
            if ($user->foto_perfil) {
                Storage::delete('public/' . $user->foto_perfil);
            }

            // Guardar nueva foto
            $path = $request->file('foto_perfil')->store('profile-photos', 'public');
            $user->foto_perfil = $path;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto actualizada correctamente',
                'path' => $path
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * CERRAR SESIÓN
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login.form')->with('toast', [
                'type' => 'success',
                'message' => 'Sesión cerrada correctamente.'
            ]);

        } catch (\Exception $e) {
            return redirect()->route('login.form')->with('toast', [
                'type' => 'error',
                'message' => 'Error al cerrar sesión.'
            ]);
        }
    }
}
