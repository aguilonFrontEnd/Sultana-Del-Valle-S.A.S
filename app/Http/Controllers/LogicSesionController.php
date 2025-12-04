<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
                'email'    => 'required|email',
                'password' => 'required|min:6'
            ]);

            // Buscar usuario por email CON la relación rol
            $user = User::with('rol')->where('email', $request->email)->first();

            // Verificar si el usuario existe
            if (!$user) {
                return redirect()->back()->with('toast', [
                    'type'    => 'error',
                    'message' => 'El usuario no existe en el sistema.'
                ])->withInput();
            }

            // Verificar si el usuario está activo
            if (!$user->estado) {
                return redirect()->back()->with('toast', [
                    'type'    => 'error',
                    'message' => 'Tu cuenta está desactivada. Contacta al administrador.'
                ])->withInput();
            }

            // Verificar contraseña
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->back()->with('toast', [
                    'type'    => 'error',
                    'message' => 'La contraseña es incorrecta.'
                ])->withInput();
            }

            // Iniciar sesión
            Auth::login($user);
            $request->session()->regenerate();

            // Redirigir a view-load
            return redirect()->route('view.load')->with('toast', [
                'type'    => 'success',
                'message' => '¡Bienvenido ' . $user->name . '!'
            ]);

        } catch (\Exception $e) {
            // Error inesperado
            return redirect()->back()->with('toast', [
                'type'    => 'error',
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
                'type'    => 'error',
                'message' => 'Debes iniciar sesión primero.'
            ]);
        }

        $user = Auth::user();

        // Cargar la relación del rol usando with()
        $userWithRol = User::with('rol')->find($user->id);

        return view('view-load', [
            'userName' => $userWithRol->name,
            'userArea' => $userWithRol->rol->nombre,
            'userRol'  => $userWithRol->rol->codigo
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
                'type'    => 'error',
                'message' => 'Debes iniciar sesión primero.'
            ]);
        }

        // Cargar la relación del rol
        $userWithRol = User::with('rol')->find($user->id);

        // Redirigir a la vista de módulos en la carpeta Modules
        return view('Modules.view-modules', [
            'user'     => $userWithRol,
            'userName' => $userWithRol->name,
            'userRol'  => $userWithRol->rol->codigo,
            'userArea' => $userWithRol->rol->nombre
        ]);
    }

    /**
     * VISTA DE CONFIGURACIÓN (acceso desde el icono de ajustes)
     */
    public function showConfig()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('toast', [
                'type'    => 'error',
                'message' => 'Debes iniciar sesión primero.'
            ]);
        }

        $user = Auth::user();
        $userWithRol = User::with('rol')->find($user->id);

        return view('Config.view-config', [
            'user'     => $userWithRol,
            'userName' => $userWithRol->name,
            'userRol'  => $userWithRol->rol->codigo,
            'userArea' => $userWithRol->rol->nombre
        ]);
    }

    /**
     * VISTA TUTORIAL ANTES DEL TABLERO
     */
    public function showTutorial($modulo)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('toast', [
                'type'    => 'error',
                'message' => 'Debes iniciar sesión primero.'
            ]);
        }

        $user = Auth::user();
        $userWithRol = User::with('rol')->find($user->id);
        $rolCodigo   = $userWithRol->rol->codigo;

        // 🔒 EL ROL CONTROL NO PUEDE VER TUTORIALES → VA DIRECTO AL TABLERO
        if ($rolCodigo === 'control') {
            return redirect()->route('tablero.show', ['modulo' => $modulo])->with('toast', [
                'type'    => 'error',
                'message' => 'Tu rol solo tiene acceso directo al tablero, no al tutorial.'
            ]);
        }

        // Validar permisos igual que en el tablero
        if (!$this->hasModuleAccess($rolCodigo, $modulo)) {
            return redirect()->route('modules')->with('toast', [
                'type'    => 'error',
                'message' => 'No tienes permisos para acceder a este módulo.'
            ]);
        }

        // Nombre “bonito” del módulo
        $moduloNombres = [
            'operativo'     => 'Operativo',
            'humanidad'     => 'Humanidad',
            'siniestros'    => 'Siniestros',
            'analistas'     => 'Analistas',
            'mantenimiento' => 'Mantenimiento',
            'documentacion' => 'Documentación',
            'liquidacion'   => 'Liquidación',
            'cartera'       => 'Cartera'
        ];

        $moduloNombre = $moduloNombres[$modulo] ?? 'General';

        /**
         * TEXTOS ESPECÍFICOS POR MÓDULO
         * (intros, KPIs e imágenes se adaptan al área)
         */

        $introsPorModulo = [
            'operativo' => 'El tablero de Operativo está diseñado para que puedas supervisar en tiempo real el comportamiento de la operación: despachos, cumplimiento de rutas, puntualidad y rendimiento por vehículo. Aquí tendrás una vista consolidada para tomar decisiones rápidas y basadas en datos frente a la operación diaria.',
            'humanidad' => 'El tablero de Humanidad está enfocado en la gestión del talento humano: capacitaciones, sanciones, procesos formativos y seguimiento al desempeño. La idea es que puedas ver, en un solo lugar, cómo se está comportando el recurso humano frente a los lineamientos de seguridad y servicio.',
            'siniestros' => 'El tablero de Siniestros te permite analizar la accidentalidad de la empresa: frecuencia, severidad, tipo de evento, vehículos involucrados y responsables. Está pensado para que identifiques patrones de riesgo y puedas proponer acciones preventivas y correctivas.',
            'analistas' => 'El tablero de Analistas centraliza la información BEA y demás fuentes analíticas. Aquí podrás cruzar datos, validar tendencias y revisar la calidad de la información que alimenta los demás tableros estratégicos.',
            'mantenimiento' => 'El tablero de Mantenimiento consolida la información de fallas, órdenes de trabajo, tiempos de inmovilización y cumplimiento de planes preventivos. Su objetivo es ayudarte a priorizar intervenciones y reducir la indisponibilidad de la flota.',
            'documentacion' => 'El tablero de Documentación reúne el estado de los documentos críticos: licencias, seguros, SOAT, tecnomecánicas, contratos y demás soportes que garantizan la operación legal de la empresa.',
            'liquidacion' => 'El tablero de Liquidación agrupa indicadores relacionados con liquidaciones, conceptos operativos y comportamiento económico asociado a los servicios prestados, permitiéndote detectar desviaciones y analizar resultados.',
            'cartera' => 'El tablero de Cartera presenta la gestión de tasas de uso y otros conceptos por cobrar, ayudándote a identificar moras, tendencias de pago y prioridades de gestión.'
        ];

        $kpisPorModulo = [
            'operativo' => [
                'Nivel de cumplimiento de despachos por ruta y franja horaria.',
                'Puntualidad de salida y llegada por vehículo y por servicio.',
                'Número de servicios realizados vs programados en la jornada.',
                'Alertas de bajo rendimiento o bajo cumplimiento por vehículo o ruta.'
            ],
            'humanidad' => [
                'Cantidad de colaboradores capacitados vs pendientes por tema.',
                'Histórico de sanciones y llamados de atención por tipo y causa.',
                'Seguimiento a cumplimiento de planes de formación.',
                'Alertas de vencimiento de evaluaciones, cursos o certificaciones.'
            ],
            'siniestros' => [
                'Número de siniestros por periodo, tipo de evento y gravedad.',
                'Vehículos y conductores con mayor recurrencia de eventos.',
                'Mapa de calor de puntos críticos o tramos de mayor riesgo.',
                'Tendencia histórica de accidentalidad y sus indicadores clave.'
            ],
            'analistas' => [
                'Consolidado BEA por periodo y fuente de información.',
                'Cruce de indicadores entre módulos (operativo, siniestros, mantenimiento, etc.).',
                'Calidad de datos: registros incompletos, duplicados o inconsistentes.',
                'Panel de seguimiento a cargas de información y actualizaciones.'
            ],
            'mantenimiento' => [
                'Fallas más frecuentes por tipo, sistema y vehículo.',
                'Tiempos de inmovilización por unidad y por tipo de mantenimiento.',
                'Cumplimiento de planes preventivos y correctivos.',
                'Costos asociados a intervenciones y su distribución en el tiempo.'
            ],
            'documentacion' => [
                'Cantidad de documentos vigentes, próximos a vencer y vencidos.',
                'Alertas por tipo de documento (SOAT, tecnomecánica, pólizas, etc.).',
                'Vista por vehículo y por conductor de su situación documental.',
                'Histórico de vencimientos y tiempos de reacción ante renovaciones.'
            ],
            'liquidacion' => [
                'Resumen de liquidaciones realizadas por periodo.',
                'Diferencias entre lo esperado vs lo liquidado por servicio.',
                'Indicadores de concepto operativo y su evolución en el tiempo.',
                'Alertas de liquidaciones atípicas o por fuera de parámetros.'
            ],
            'cartera' => [
                'Saldo total de cartera por concepto y por periodo.',
                'Antigüedad de la cartera (0-30, 31-60, 61-90, +90 días).',
                'Clientes o responsables con mayor saldo pendiente.',
                'Tendencias de recaudo y efectividad de la gestión de cobro.'
            ],
        ];

        // Rutas de imágenes de ejemplo por módulo (puedes cambiarlas luego)
        $imagenesPorModulo = [
            'operativo'     => ['Images/Tutorial/operativo_1.png', 'Images/Tutorial/operativo_2.png'],
            'humanidad'     => ['Images/Tutorial/humanidad_1.png'],
            'siniestros'    => ['Images/Tutorial/siniestros_1.png', 'Images/Tutorial/siniestros_2.png'],
            'analistas'     => ['Images/Tutorial/analistas_1.png'],
            'mantenimiento' => ['Images/Tutorial/mantenimiento_1.png'],
            'documentacion' => ['Images/Tutorial/documentacion_1.png'],
            'liquidacion'   => ['Images/Tutorial/liquidacion_1.png'],
            'cartera'       => ['Images/Tutorial/cartera_1.png'],
        ];

        /**
         * BLOQUES GENERALES (SE APLICAN A TODOS LOS MÓDULOS, PERO
         * SE PERSONALIZAN CON EL NOMBRE DEL MÓDULO)
         */

        $stepsTemplates = [
            'En la parte superior del tablero de {modulo} encontrarás la barra de navegación principal, donde podrás cambiar entre páginas o vistas (por ejemplo: resumen, detalle, histórico y alertas).',
            'En el panel izquierdo o superior se ubican los filtros principales: fecha, vehículo, ruta, colaborador, tipo de evento y demás variables clave. Selecciona primero el periodo que deseas analizar.',
            'Cuando apliques un filtro, verifica que todos los gráficos se actualicen de forma sincronizada. Esto te garantiza que estás leyendo el contexto correcto del módulo de {modulo}.',
            'Pasa el cursor (mouse) por encima de las tarjetas e indicadores numéricos para ver detalles adicionales: valores exactos, porcentajes y descripciones de cada KPI.',
            'En los mapas, matrices o gráficos de barras puedes hacer clic sobre un elemento (por ejemplo, una ruta, un vehículo o un tipo de siniestro) para hacer “zoom” analítico sobre ese segmento específico.',
            'Utiliza los botones o pestañas de navegación interna del tablero (si están configurados) para moverte entre vistas: resumen ejecutivo, análisis detallado, histórico y tablas de soporte.',
            'Al final de la página normalmente encontrarás tablas detalladas con la información de soporte. Estas tablas suelen permitir ordenar columnas, buscar valores específicos o exportar la información.',
            'Si en algún momento el tablero se ve “congelado” o los datos no parecen actualizarse, presiona el botón de actualizar en Power BI o recarga la página del navegador.',
        ];

        $recomendacionesTemplates = [
            'Define siempre un rango de fechas antes de sacar conclusiones; evita analizar datos sin filtrar el periodo correcto.',
            'Cruza por lo menos dos dimensiones a la vez (por ejemplo: ruta + vehículo, siniestro + conductor, documento + fecha de vencimiento) para tener una lectura más completa.',
            'Si ves un valor extremo (muy alto o muy bajo), revisa la tabla de detalle asociada para validar si hay errores de digitación o si realmente es un comportamiento atípico.',
            'No te quedes solo con el primer gráfico: recorre todo el tablero de {modulo} de arriba hacia abajo para entender la historia completa que está contando la data.',
            'Toma nota de los hallazgos claves (por ejemplo: vehículos críticos, rutas con más eventos, documentos próximos a vencer) y compártelos en comité o con las áreas responsables.',
            'Si necesitas presentar la información, utiliza las tarjetas y gráficos principales como “portada visual” del análisis y apóyate en las tablas cuando te hagan preguntas específicas.',
            'Vuelve a este tutorial cada vez que lo necesites: la idea es que sirva como guía práctica para que cualquier persona del área pueda navegar el tablero sin perderse.'
        ];

        // Personalizar steps y recomendaciones con el nombre del módulo
        $steps = [];
        foreach ($stepsTemplates as $s) {
            $steps[] = str_replace('{modulo}', strtolower($moduloNombre), $s);
        }

        $recomendaciones = [];
        foreach ($recomendacionesTemplates as $r) {
            $recomendaciones[] = str_replace('{modulo}', strtolower($moduloNombre), $r);
        }

        // Armar el tutorial final para el módulo solicitado
        $tutorial = [
            'intro'           => $introsPorModulo[$modulo] ?? 'Este tablero está diseñado para que puedas analizar la información clave del módulo de ' . $moduloNombre . ' de forma visual, clara e interactiva.',
            'kpis'            => $kpisPorModulo[$modulo] ?? [
                'Indicadores clave de desempeño del módulo ' . $moduloNombre . '.',
                'Evolución histórica de las métricas más relevantes.',
                'Alertas y focos de atención prioritaria.',
                'Tablas de detalle con la información de soporte.'
            ],
            'steps'           => $steps,
            'recomendaciones' => $recomendaciones,
            'imagenes'        => $imagenesPorModulo[$modulo] ?? []
        ];

        return view('Tutorial.view-tutorial', [
            'user'         => $userWithRol,
            'userName'     => $userWithRol->name,
            'userArea'     => $userWithRol->rol->nombre,
            'userRol'      => $rolCodigo,
            'moduloNombre' => $moduloNombre,
            'moduloId'     => $modulo,
            'tutorial'     => $tutorial
        ]);
    }

    /**
     * MOSTRAR TABLERO POWER BI
     */
    public function showTablero($modulo)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('toast', [
                'type'    => 'error',
                'message' => 'Debes iniciar sesión primero.'
            ]);
        }

        $user = Auth::user();
        $userWithRol = User::with('rol')->find($user->id);

        if (!$this->hasModuleAccess($userWithRol->rol->codigo, $modulo)) {
            return redirect()->route('modules')->with('toast', [
                'type'    => 'error',
                'message' => 'No tienes permisos para acceder a este módulo.'
            ]);
        }

        $moduloNombres = [
            'operativo'     => 'Operativo',
            'humanidad'     => 'Humanidad',
            'siniestros'    => 'Siniestros',
            'analistas'     => 'Analistas',
            'mantenimiento' => 'Mantenimiento',
            'documentacion' => 'Documentación',
            'liquidacion'   => 'Liquidación',
            'cartera'       => 'Cartera'
        ];

        $powerbiUrls = [
            'operativo'     => 'https://app.powerbi.com/reportEmbed?reportId=c9aec2c0-56d3-4df5-83f3-c170b09f08ad&autoAuth=true&ctid=bfcae52b-3054-486c-998b-518ff055dcaa',
            'documentacion' => 'https://app.powerbi.com/reportEmbed?reportId=b8a42692-073a-49e7-95f2-73c99ad2c432&autoAuth=true&ctid=bfcae52b-3054-486c-998b-518ff055dcaa',
            'siniestros'    => 'https://app.powerbi.com/reportEmbed?reportId=20a9942b-09a2-4df8-ad13-b6cfd66caa2d&autoAuth=true&ctid=bfcae52b-3054-486c-998b-518ff055dcaa',
            'mantenimiento' => 'https://app.powerbi.com/reportEmbed?reportId=2a26394d-6c84-4d9c-b396-5257114a9ff1&autoAuth=true&ctid=bfcae52b-3054-486c-998b-518ff055dcaa',
            'analistas'     => 'https://app.powerbi.com/reportEmbed?reportId=bc3e7363-4d20-42d5-8fdb-9813be575122&autoAuth=true&ctid=bfcae52b-3054-486c-998b-518ff055dcaa',
            'humanidad'     => 'https://app.powerbi.com/reportEmbed?reportId=cbadc814-1a0d-42cc-bc73-21176b2e2590&autoAuth=true&ctid=bfcae52b-3054-486c-998b-518ff055dcaa',
            'liquidacion'   => '#',
            'cartera'       => '#'
        ];

        return view('Table.view-tables', [
            'user'         => $userWithRol,
            'userName'     => $userWithRol->name,
            'userArea'     => $userWithRol->rol->nombre,
            'userRol'      => $userWithRol->rol->codigo,
            'moduloNombre' => $moduloNombres[$modulo] ?? 'General',
            'moduloId'     => $modulo,
            'powerbiUrl'   => $powerbiUrls[$modulo] ?? '#'
        ]);
    }

    /**
     * Verificar acceso al módulo - NUEVA LÓGICA
     */
    private function hasModuleAccess($userRolCodigo, $moduleCodigo)
    {
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
     * CERRAR SESIÓN
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login.form')->with('toast', [
                'type'    => 'success',
                'message' => 'Sesión cerrada correctamente.'
            ]);

        } catch (\Exception $e) {
            return redirect()->route('login.form')->with('toast', [
                'type'    => 'error',
                'message' => 'Error al cerrar sesión.'
            ]);
        }
    }
}
