{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Sultana del Valle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-sultana-gradient {
            background: linear-gradient(135deg, #0A35FF 0%, #FF7A00 50%, #FF9B2F 100%);
        }
        .icon-bounce {
            animation: microBounce 3s infinite;
        }
        @keyframes microBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
    </style>
</head>
<body class="bg-sultana-gradient min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden text-center p-8">
        {{-- Logo --}}
        <div class="mb-6">
            <img src="{{ asset('Images/Logo.jpg') }}"
                 alt="Sultana del Valle S.A.S."
                 class="mx-auto h-20 w-auto">
        </div>

        {{-- Mensaje de bienvenida --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-[#0A35FF] mb-2">¡Hola, {{ $userName }}!</h1>
            <p class="text-xl text-[#0F172A]">Área: <span class="font-semibold text-[#FF7A00]">{{ $userArea }}</span></p>
        </div>

        {{-- Loading animation con ícono que hace microsaltos --}}
        <div class="flex flex-col items-center justify-center mb-4">
            <div class="icon-bounce mb-2">
                <svg class="w-12 h-12 text-[#0A35FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="animate-pulse text-[#0A35FF] font-semibold">
                Cargando tableros de información...
            </div>
        </div>

        <p class="text-[#64748B] text-sm">Preparando tu experiencia analítica</p>
    </div>

    <script>
        // Redirección automática después de 5 segundos a MÓDULOS
        setTimeout(() => {
            window.location.href = "{{ route('modules') }}";
        }, 5000);
    </script>

</body>
</html>
