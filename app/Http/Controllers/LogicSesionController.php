<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Rol;

class LogicSesionController extends Controller
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
     * REDIRIGIR A LA VISTA DE MÓDULOS
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
     * MOSTRAR TABLERO POWER BI
     */
   public function showTablero($modulo) {
    if (!Auth::check()) {
        return redirect()->route('login.form')->with('toast', [
            'type' => 'error',
            'message' => 'Debes iniciar sesión primero.'
        ]);
    }

    $user = Auth::user();
    $userWithRol = User::with('rol')->find($user->id);

    if (!$this->hasModuleAccess($userWithRol->rol->codigo, $modulo)) {
        return redirect()->route('modules')->with('toast', [
            'type' => 'error',
            'message' => 'No tienes permisos para acceder a este módulo.'
        ]);
    }

    $moduloNombres = [
        'operativo' => 'Operativo',
        'humanidad' => 'Humanidad',
        'siniestros' => 'Siniestros',
        'analistas' => 'Analistas',
        'mantenimiento' => 'Mantenimiento',
        'documentacion' => 'Documentación',
        'liquidacion' => 'Liquidación',
        'cartera' => 'Cartera'
    ];

    $powerbiUrls = [
        'operativo' => 'https://app.powerbi.com/reportEmbed?reportId=c9aec2c0-56d3-4df5-83f3-c170b09f08ad&autoAuth=true&ctid=bfcae52b-3054-486c-998b-518ff055dcaa',
        'documentacion' => 'https://app.powerbi.com/reportEmbed?reportId=b8a42692-073a-49e7-95f2-73c99ad2c432&autoAuth=true&ctid=bfcae52b-3054-486c-998b-518ff055dcaa',
        'siniestros' => 'https://app.powerbi.com/reportEmbed?reportId=20a9942b-09a2-4df8-ad13-b6cfd66caa2d&autoAuth=true&ctid=bfcae52b-3054-486c-998b-518ff055dcaa',
        'mantenimiento' => 'https://app.powerbi.com/reportEmbed?reportId=2a26394d-6c84-4d9c-b396-5257114a9ff1&autoAuth=true&ctid=bfcae52b-3054-486c-998b-518ff055dcaa',
        'analistas' => 'https://app.powerbi.com/reportEmbed?reportId=bc3e7363-4d20-42d5-8fdb-9813be575122&autoAuth=true&ctid=bfcae52b-3054-486c-998b-518ff055dcaa',
        'humanidad' => 'https://app.powerbi.com/reportEmbed?reportId=cbadc814-1a0d-42cc-bc73-21176b2e2590&autoAuth=true&ctid=bfcae52b-3054-486c-998b-518ff055dcaa',
        'liquidacion' => '#',
        'cartera' => '#'
    ];

    // VERIFICA QUE ESTA LÍNEA ESTÉ EXACTA:
    return view('Table.view-tables', [
        'user' => $userWithRol,
        'userName' => $userWithRol->name,
        'userArea' => $userWithRol->rol->nombre,
        'userRol' => $userWithRol->rol->codigo,
        'moduloNombre' => $moduloNombres[$modulo] ?? 'General',
        'moduloId' => $modulo,
        'powerbiUrl' => $powerbiUrls[$modulo] ?? '#'
        ]);
    }

    /**
     * Verificar acceso al módulo - NUEVA LÓGICA
     */
    private function hasModuleAccess($userRolCodigo, $moduleCodigo) {
        // Control e Informe pueden ver todos los módulos
        if ($userRolCodigo === 'control' || $userRolCodigo === 'informe') {
            return true;
        }

        // Operativo puede ver analistas y operativo
        if ($userRolCodigo === 'operativo') {
            return $moduleCodigo === 'analistas' || $moduleCodigo === 'operativo';
        }

        // Contadora puede ver cartera y liquidacion
        if ($userRolCodigo === 'contadora') {
            return $moduleCodigo === 'cartera' || $moduleCodigo === 'liquidacion';
        }

        // Cartera solo puede ver cartera
        if ($userRolCodigo === 'cartera') {
            return $moduleCodigo === 'cartera';
        }

        // Liquidacion solo puede ver liquidacion
        if ($userRolCodigo === 'liquidacion') {
            return $moduleCodigo === 'liquidacion';
        }

        // Humanidad solo puede ver humanidad
        if ($userRolCodigo === 'humanidad') {
            return $moduleCodigo === 'humanidad';
        }

        // Siniestros solo puede ver siniestros
        if ($userRolCodigo === 'siniestros') {
            return $moduleCodigo === 'siniestros';
        }

        // Documentacion solo puede ver documentacion
        if ($userRolCodigo === 'documentacion') {
            return $moduleCodigo === 'documentacion';
        }

        // Mantenimiento solo puede ver mantenimiento
        if ($userRolCodigo === 'mantenimiento') {
            return $moduleCodigo === 'mantenimiento';
        }

        // Analistas solo puede ver analistas
        if ($userRolCodigo === 'analistas') {
            return $moduleCodigo === 'analistas';
        }

        // Por defecto, solo puede ver su propio módulo
        return $userRolCodigo === $moduleCodigo;
    }

    /**
     * ACTUALIZAR FOTO DE PERFIL
     */
    public function updatePhoto(Request $request) {
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
    public function logout(Request $request) {
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
