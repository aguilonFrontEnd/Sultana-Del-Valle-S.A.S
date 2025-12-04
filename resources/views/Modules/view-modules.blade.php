<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Tableros Estadísticos - Sultana del Valle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* PALETA CORPORATIVA SULTANA DEL VALLE */
        :root {
            --sultana-azul: #0A35FF;
            --sultana-azul-oscuro: #0830E0;
            --sultana-naranja: #FF7A00;
            --sultana-naranja-claro: #FF9B2F;
            --sultana-gris: #F4F6F8;
            --sultana-texto: #0F172A;
        }

        .bg-sultana-azul {
            background: linear-gradient(135deg, var(--sultana-azul) 0%, var(--sultana-azul-oscuro) 100%);
        }

        .bg-sultana-gris {
            background-color: var(--sultana-gris);
        }

        .module-card {
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
            height: 200px;
            background: white;
        }

        .module-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(10, 53, 255, 0.1), 0 4px 10px -2px rgba(10, 53, 255, 0.05);
            border-color: var(--sultana-azul);
        }

        .module-active {
            opacity: 1;
            border: 2px solid #e2e8f0;
        }

        .module-blocked {
            opacity: 0.6;
            filter: grayscale(40%);
            border: 2px solid #e2e8f0;
        }

        .icon-bounce {
            animation: microBounce 3s infinite;
        }

        @keyframes microBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-2px); }
        }

        .header {
            padding: 15px 20px;
            background: linear-gradient(135deg, var(--sultana-azul) 0%, var(--sultana-azul-oscuro) 100%);
            border-bottom: 3px solid var(--sultana-naranja);
            color: white;
        }

        .info-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            background-color: var(--sultana-gris);
            font-size: 14px;
            border-bottom: 1px solid #e2e8f0;
            color: var(--sultana-texto);
        }

        body {
            background-color: white;
            color: var(--sultana-texto);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .title-section {
            flex: 1;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body class="min-h-screen">

    {{-- HEADER CORPORATIVO --}}
    <div class="header">
        <div class="header-content">
            <div class="title-section">
                <h1 class="text-2xl font-bold">SISTEMA DE TABLEROS ESTADÍSTICOS</h1>
                <h2 class="text-lg opacity-90">Transportes Sultana del Valle S.A.S</h2>
            </div>

            <div class="header-user">
                @php
                    // Módulo por defecto para el ícono de ayuda (según rol)
                    // Para roles que solo ven un módulo usamos ese; si no, por defecto "operativo"
                    $moduloAyuda = $userRol ?: 'operativo';
                @endphp

                {{-- SI ES ROL CONTROL → ICONO DE CONFIG --}}
                @if($user->rol->codigo === 'control')
                    <button type="button"
                            class="p-1"
                            title="Ajustes del sistema"
                            onclick="window.location.href='{{ route('config') }}'">
                        <i class="fas fa-sliders-h text-white text-2xl"></i>
                    </button>

                {{-- SI ES ROL INFORME → SIN ICONO --}}
                @elseif($user->rol->codigo === 'informe')
                    {{-- Rol informe no muestra ningún icono en el header --}}

                {{-- CUALQUIER OTRO ROL → ICONO DE PREGUNTA QUE LLEVA AL TUTORIAL --}}
                @else
                    <button type="button"
                            class="p-1"
                            title="Ver tutorial del sistema"
                            onclick="window.location.href='{{ route('tablero.tutorial', ['modulo' => $moduloAyuda]) }}'">
                        <i class="fas fa-question-circle text-white text-2xl"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- BARRA DE INFORMACIÓN CORPORATIVA --}}
    <div class="info-bar">
        <div class="date-time" id="date-time">Cargando fecha y hora...</div>
        <div class="additional-info font-semibold text-sultana-azul" id="area-info">
            Bienvenido al área de {{ $userArea }}
        </div>
    </div>

    {{-- CONTENIDO PRINCIPAL - MÓDULOS --}}
    <main class="container mx-auto px-6 py-8">
        {{-- GRID DE MÓDULOS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 max-w-6xl mx-auto" id="modules-container">
            <!-- Los módulos se cargarán dinámicamente con JavaScript según el rol -->
        </div>
    </main>

    <script>
        // Datos de los módulos ACTUALIZADOS - CON CARTERA
        const modules = [
            {
                id: 'operativo',
                title: 'Operativo',
                description: 'Gestión operativa y logística',
                icon: 'fa-truck',
                codigo: 'operativo',
                color: 'blue'
            },
            {
                id: 'humanidad',
                title: 'Humanidad',
                description: 'Gestión en capacitaciones y multas',
                icon: 'fa-users',
                codigo: 'humanidad',
                color: 'orange'
            },
            {
                id: 'siniestros',
                title: 'Siniestros',
                description: 'Gestión de riesgos y accidentes',
                icon: 'fa-exclamation-triangle',
                codigo: 'siniestros',
                color: 'red'
            },
            {
                id: 'analistas',
                title: 'Analistas',
                description: 'Gestión BEA',
                icon: 'fa-chart-bar',
                codigo: 'analistas',
                color: 'purple'
            },
            {
                id: 'mantenimiento',
                title: 'Mantenimiento',
                description: 'Gestión de mantenimiento y fallas',
                icon: 'fa-tools',
                codigo: 'mantenimiento',
                color: 'yellow'
            },
            {
                id: 'documentacion',
                title: 'Documentación',
                description: 'Gestión documental y alertas',
                icon: 'fa-file-contract',
                codigo: 'documentacion',
                color: 'green'
            },
            {
                id: 'liquidacion',
                title: 'Liquidación',
                description: 'Gestión de liquidación y concepto',
                icon: 'fa-calculator',
                codigo: 'liquidacion',
                color: 'indigo'
            },
            {
                id: 'cartera',
                title: 'Cartera',
                description: 'Gestión de tasas de uso',
                icon: 'fa-money-bill-wave',
                codigo: 'cartera',
                color: 'emerald'
            }
        ];

        // Datos del usuario desde Laravel
        const userData = {
            id: {{ $user->id }},
            name: "{{ $userName }}",
            rol: {
                id: {{ $user->rol->id }},
                nombre: "{{ $userArea }}",
                codigo: "{{ $userRol }}"
            }
        };

        // ========= PERMISOS POR ROL =========
        function isModuleActive(moduleCodigo) {
            const userRolCodigo = userData.rol.codigo;

            // Control e Informe pueden ver todos los módulos
            if (userRolCodigo === 'control' || userRolCodigo === 'informe') {
                return true;
            }

            // Operativo puede ver analistas y operativo
            if (userRolCodigo === 'operativo') {
                return moduleCodigo === 'analistas' || moduleCodigo === 'operativo';
            }

            // Contadora puede ver cartera y liquidacion
            if (userRolCodigo === 'contadora') {
                return moduleCodigo === 'cartera' || moduleCodigo === 'liquidacion';
            }

            // Cartera solo puede ver cartera
            if (userRolCodigo === 'cartera') {
                return moduleCodigo === 'cartera';
            }

            // Liquidacion solo puede ver liquidacion
            if (userRolCodigo === 'liquidacion') {
                return moduleCodigo === 'liquidacion';
            }

            // Humanidad solo puede ver humanidad
            if (userRolCodigo === 'humanidad') {
                return moduleCodigo === 'humanidad';
            }

            // Siniestros solo puede ver siniestros
            if (userRolCodigo === 'siniestros') {
                return moduleCodigo === 'siniestros';
            }

            // Documentacion solo puede ver documentacion
            if (userRolCodigo === 'documentacion') {
                return moduleCodigo === 'documentacion';
            }

            // Mantenimiento solo puede ver mantenimiento
            if (userRolCodigo === 'mantenimiento') {
                return moduleCodigo === 'mantenimiento';
            }

            // Analistas solo puede ver analistas
            if (userRolCodigo === 'analistas') {
                return moduleCodigo === 'analistas';
            }

            // Por defecto, solo puede ver su propio módulo
            return userRolCodigo === moduleCodigo;
        }

        function updateDateTime() {
            const now = new Date();
            const optionsDate = {
                weekday: 'long',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const dateTimeStr = now.toLocaleDateString('es-CO', optionsDate);
            document.getElementById('date-time').textContent = dateTimeStr;
        }

        function renderModules() {
            const container = document.getElementById('modules-container');
            container.innerHTML = '';

            modules.forEach(module => {
                const isActive = isModuleActive(module.codigo);

                const moduleCard = document.createElement('div');
                moduleCard.className = `module-card rounded-xl p-5 shadow-lg ${isActive ? 'module-active icon-bounce' : 'module-blocked'}`;

                const colorClasses = {
                    'operativo': {
                        bg: 'bg-[#0A35FF]',
                        text: 'text-[#0A35FF]',
                        bg_opacity: 'bg-[#0A35FF] bg-opacity-10'
                    },
                    'humanidad': {
                        bg: 'bg-[#FF7A00]',
                        text: 'text-[#FF7A00]',
                        bg_opacity: 'bg-[#FF7A00] bg-opacity-10'
                    },
                    'siniestros': {
                        bg: 'bg-[#DC2626]',
                        text: 'text-[#DC2626]',
                        bg_opacity: 'bg-[#DC2626] bg-opacity-10'
                    },
                    'analistas': {
                        bg: 'bg-[#7C3AED]',
                        text: 'text-[#7C3AED]',
                        bg_opacity: 'bg-[#7C3AED] bg-opacity-10'
                    },
                    'mantenimiento': {
                        bg: 'bg-[#D97706]',
                        text: 'text-[#D97706]',
                        bg_opacity: 'bg-[#D97706] bg-opacity-10'
                    },
                    'documentacion': {
                        bg: 'bg-[#059669]',
                        text: 'text-[#059669]',
                        bg_opacity: 'bg-[#059669] bg-opacity-10'
                    },
                    'liquidacion': {
                        bg: 'bg-[#4F46E5]',
                        text: 'text-[#4F46E5]',
                        bg_opacity: 'bg-[#4F46E5] bg-opacity-10'
                    },
                    'cartera': {
                        bg: 'bg-[#10B981]',
                        text: 'text-[#10B981]',
                        bg_opacity: 'bg-[#10B981] bg-opacity-10'
                    }
                };

                const colors = colorClasses[module.codigo] || colorClasses.operativo;

                moduleCard.innerHTML = `
                    <div class="text-center h-full flex flex-col justify-between">
                        <div>
                            <div class="p-3 inline-block mb-3">
                                <i class="fas ${module.icon} ${colors.text} text-xl"></i>
                            </div>
                            <h3 class="text-base font-semibold mb-1 text-sultana-texto">${module.title}</h3>
                            <p class="text-slate-600 text-xs mb-3 leading-tight">${module.description}</p>
                        </div>
                        <button class="${colors.bg} text-white px-3 py-2 rounded-lg font-medium hover:opacity-90 transition-all w-full text-sm ${!isActive ? 'opacity-70 cursor-not-allowed' : ''}"
                                onclick="accessModule('${module.id}', ${isActive}, '${module.title}')">
                            ${isActive ? 'VISUALIZAR' : 'BLOQUEADO'}
                        </button>
                    </div>
                `;

                container.appendChild(moduleCard);
            });
        }

        // Aquí definimos el flujo según el rol
        function accessModule(moduleId, hasAccess, moduleTitle) {
            if (!hasAccess) {
                alert('❌ No tienes permisos para acceder al módulo: ' + moduleTitle);
                return;
            }

            const userRolCodigo = "{{ $userRol }}";

            if (userRolCodigo === 'control') {
                // CONTROL va directo al tablero Power BI
                window.location.href = `/tablero/${moduleId}`;
            } else {
                // Cualquier otro rol (incluido INFORME) va primero a la vista TUTORIAL
                window.location.href = `/tablero/${moduleId}/tutorial`;
            }
        }

        // Inicializar
        document.addEventListener('DOMContentLoaded', () => {
            updateDateTime();
            renderModules();
            setInterval(updateDateTime, 1000);
        });
    </script>

</body>
</html>
