{{-- resources/views/form/view-form.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Analítico de Datos - Sultana del Valle S.A.S.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-sultana-gradient {
            background: linear-gradient(135deg, #0A35FF 0%, #FF7A00 50%, #FF9B2F 100%);
        }
        .btn-sultana {
            background: #0A35FF;
            transition: all 0.3s ease;
        }
        .btn-sultana:hover {
            background:#E56D00;
            transform: translateY(-2px);
        }

        /* Animaciones personalizadas del formulario */
        .form-container {
            animation: slideInFromLeft 0.6s ease-out forwards;
        }

        .form-container.slide-out {
            animation: slideOutToRight 0.6s ease-in forwards;
        }

        @keyframes slideInFromLeft {
            0% {
                opacity: 0;
                transform: translateX(-105vw);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOutToRight {
            0% {
                opacity: 1;
                transform: translateX(0);
            }
            100% {
                opacity: 0;
                transform: translateX(105vw);
            }
        }

        /* Estilos de los toasts */
        .toast-success {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            border-left: 4px solid #047857;
        }

        .toast-error {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            border-left: 4px solid #B91C1C;
        }

        .toast-progress {
            height: 3px;
            background: rgba(255,255,255,0.3);
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 100%;
            animation: progress 5s linear forwards;
        }

        @keyframes progress {
            0% { width: 100%; }
            100% { width: 0%; }
        }

        /* Animaciones de toasts */
        @keyframes toastSlideInLeft {
            from { transform: translateX(-100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes toastSlideInRight {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes toastSlideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100px); opacity: 0; }
        }

        .animate-toast-left {
            animation: toastSlideInLeft 0.5s ease-out forwards;
        }

        .animate-toast-right {
            animation: toastSlideInRight 0.5s ease-out forwards;
        }

        .animate-toast-out {
            animation: toastSlideOut 0.5s ease-in forwards;
        }
    </style>
</head>
<body class="overflow-hidden bg-sultana-gradient min-h-screen flex items-center justify-center p-4">

    {{-- Contenedor del formulario --}}
    <div id="loginForm" class="form-container max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- HEADER CON LOGO --}}
        <div class="bg-white py-2 px-5 text-center">
            <div class="mb-2">
                <img src="{{ asset('Images/Logo.jpg') }}"
                     alt="Sultana del Valle S.A.S."
                     class="mx-auto h-[12vw] w-auto">
            </div>
            <h1 class="text-2xl font-bold text-[#0F172A]">Sistema Analítico de Datos</h1>
        </div>

        {{-- FORMULARIO DE LOGIN --}}
        <div class="px-8 pb-8">
            <form id="loginFormElement" action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                {{-- CAMPO EMAIL --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-[#0F172A] mb-2">
                        Correo Electrónico
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           required
                           class="outline-none w-full px-4 py-3 border border-[#E2E8F0] rounded-lg focus:ring-2 focus:ring-[#0A35FF] focus:border-transparent transition-all duration-200"
                           placeholder="usuario@sultana.com"
                           value="{{ old('email') }}">
                </div>

                {{-- CAMPO CONTRASEÑA --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-[#0F172A] mb-2">
                        Contraseña
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           class="outline-none w-full px-4 py-3 border border-[#E2E8F0] rounded-lg focus:ring-2 focus:ring-[#0A35FF] focus:border-transparent transition-all duration-200"
                           placeholder="••••••••">
                </div>

                {{-- BOTÓN INICIAR SESIÓN --}}
                <button type="submit"
                        class="w-full btn-sultana text-white py-3 px-4 rounded-lg font-semibold text-lg shadow-lg hover:shadow-xl transition-all duration-300">
                    Iniciar Sesión
                </button>

                {{-- ENLACE RECUPERAR CONTRASEÑA --}}
                <div class="text-center pt-4">
                    <a href="#" class="text-[#0A35FF] hover:text-[#0830E0] font-medium text-sm transition-colors duration-200">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Sistema de Toasts --}}
    <div id="toastContainer" class="fixed top-4 z-50 w-full max-w-sm"></div>

    {{-- LOGICA PARA LAS ALERTAS TOAST Y ANIMACIONES DEL FORMULARIO --}}
    <script>
        // Animación del formulario al enviar
        document.getElementById('loginFormElement').addEventListener('submit', function(e) {
            e.preventDefault();

            // Agregar clase de animación de salida
            const formContainer = document.getElementById('loginForm');
            formContainer.classList.add('slide-out');

            // Esperar a que termine la animación y luego enviar el formulario
            setTimeout(() => {
                this.submit();
            }, 600); // 0.6s = duración de la animación (CORREGIDO)
        });

        // Sistema de toasts automático
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('toast'))
                showToast(@json(session('toast')));
            @endif

            function showToast(toastData) {
                const container = document.getElementById('toastContainer');
                const isSuccess = toastData.type === 'success';
                const positionClass = isSuccess ? 'left-4 animate-toast-left' : 'right-4 animate-toast-right';
                const toastColor = isSuccess ? 'green' : 'red';

                const toastHTML = `
                    <div class="toast-message bg-white rounded-lg shadow-xl p-4 mb-3 relative overflow-hidden border-l-4 border-${toastColor}-600 ${positionClass}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                ${isSuccess ?
                                    `<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>` :
                                    `<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>`
                                }
                            </div>
                            <div class="ml-1 flex-1">
                                <p class="text-sm font-medium text-gray-900">${toastData.message}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="toast-progress"></div>
                    </div>
                `;

                // Agregar el toast al contenedor
                container.insertAdjacentHTML('beforeend', toastHTML);

                // Auto-remover después de 5 segundos
                setTimeout(() => {
                    const toast = container.querySelector('.toast-message');
                    if (toast) {
                        toast.classList.remove('animate-toast-left', 'animate-toast-right');
                        toast.classList.add('animate-toast-out');
                        setTimeout(() => toast.remove(), 500);
                    }
                }, 5000);
            }
        });

        // Mostrar errores de validación de Laravel
        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                showToast({
                    type: 'error',
                    message: '{{ $errors->first() }}'
                });
            });
        @endif
    </script>

</body>
</html>
