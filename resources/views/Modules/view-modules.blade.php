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
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--sultana-azul) 0%, var(--sultana-naranja) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: 2px solid white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }
        .profile-pic input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .profile-pic:hover::after {
            content: '📷';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            border-radius: 10px;
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
            gap: 15px;
        }

        /* Colores corporativos para módulos */
        .text-sultana-azul { color: var(--sultana-azul); }
        .text-sultana-naranja { color: var(--sultana-naranja); }
        .text-sultana-texto { color: var(--sultana-texto); }

        .border-sultana-azul { border-color: var(--sultana-azul); }
        .border-sultana-naranja { border-color: var(--sultana-naranja); }
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
                <!-- Foto de perfil corporativa -->
                <div class="profile-pic" id="profilePicContainer">
                    @if($user->foto_perfil)
                        <img src="{{ asset('storage/' . $user->foto_perfil) }}" alt="Foto de perfil" id="profileImage">
                    @else
                        <i class="fas fa-user" id="profileIcon"></i>
                    @endif
                    <input type="file" id="profilePhotoInput" accept="image/*" style="display: none;">
                </div>
            </div>
        </div>
    </div>

    {{-- BARRA DE INFORMACIÓN CORPORATIVA --}}
    <div class="info-bar">
        <div class="date-time" id="date-time">Cargando fecha y hora...</div>
        <div class="additional-info font-semibold text-sultana-azul" id="area-info">
            Bienvenido Al Area de {{ $userArea }}
        </div>
    </div>

    {{-- CONTENIDO PRINCIPAL - MÓDULOS --}}
    <main class="container mx-auto px-6 py-8">
        {{-- GRID DE MÓDULOS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 max-w-6xl mx-auto" id="modules-container">
            <!-- Los módulos se cargarán dinámicamente con JavaScript según el rol -->
        </div>
    </main>

    {{-- SCRIPT PARA HORA EN TIEMPO REAL, LÓGICA DE PERMISOS Y FOTO DE PERFIL --}}
    <script>
        // Datos de los módulos
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
                id: 'configuracion',
                title: 'Configuración',
                description: 'Configuración del sistema',
                icon: 'fa-cogs',
                codigo: 'configuracion',
                config: true,
                color: 'gray'
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

        // ========== FUNCIONALIDAD FOTO DE PERFIL ==========
        document.getElementById('profilePicContainer').addEventListener('click', function() {
            document.getElementById('profilePhotoInput').click();
        });

        document.getElementById('profilePhotoInput').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];

                if (!file.type.match('image.*')) {
                    alert('❌ Por favor selecciona una imagen válida');
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert('❌ La imagen debe ser menor a 2MB');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const profileIcon = document.getElementById('profileIcon');
                    const profileImage = document.getElementById('profileImage');

                    if (profileIcon) profileIcon.style.display = 'none';
                    if (!profileImage) {
                        const img = document.createElement('img');
                        img.id = 'profileImage';
                        img.src = e.target.result;
                        img.alt = 'Foto de perfil';
                        img.className = 'w-full h-full object-cover rounded-[10px]';
                        document.getElementById('profilePicContainer').appendChild(img);
                    } else {
                        profileImage.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);

                uploadProfilePhoto(file);
            }
        });

        function uploadProfilePhoto(file) {
            const formData = new FormData();
            formData.append('foto_perfil', file);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');

            fetch('{{ route("profile.update-photo") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Foto de perfil actualizada correctamente');
                } else {
                    alert('❌ Error al actualizar la foto: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Error al subir la imagen');
            });
        }

        // ========== FUNCIONES EXISTENTES ==========
        function isModuleActive(moduleCodigo) {
            const userRolCodigo = userData.rol.codigo;
            if (userRolCodigo === 'control') return true;
            if (userRolCodigo === 'informe') return moduleCodigo !== 'configuracion';
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

                // Colores corporativos mejorados
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
                    'configuracion': {
                        bg: 'bg-[#6B7280]',
                        text: 'text-[#6B7280]',
                        bg_opacity: 'bg-[#6B7280] bg-opacity-10'
                    }
                };

                const colors = colorClasses[module.codigo] || colorClasses.configuracion;

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
                            ${isActive ? (module.config ? 'CONFIGURAR' : 'VISUALIZAR') : 'BLOQUEADO'}
                        </button>
                    </div>
                `;

                container.appendChild(moduleCard);
            });
        }

        function accessModule(moduleId, hasAccess, moduleTitle) {
            if (!hasAccess) {
                alert('❌ No tienes permisos para acceder al módulo: ' + moduleTitle);
                return;
            }
            window.location.href = `/tablero/${moduleId}`;
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