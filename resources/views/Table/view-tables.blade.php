<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero {{ $moduloNombre }} - Sultana del Valle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sultana-azul: #0A35FF;
            --sultana-azul-oscuro: #0830E0;
            --sultana-naranja: #FF7A00;
        }

        .bg-sultana-azul {
            background: linear-gradient(135deg, var(--sultana-azul) 0%, var(--sultana-azul-oscuro) 100%);
        }

        .btn-volver {
            background: linear-gradient(135deg, var(--sultana-azul) 0%, var(--sultana-azul-oscuro) 100%);
            transition: all 0.3s ease;
        }

        .btn-volver:hover {
            background: linear-gradient(135deg, var(--sultana-azul-oscuro) 0%, var(--sultana-naranja) 100%);
            transform: translateY(-2px);
        }

        .btn-logout {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            transform: translateY(-2px);
        }

        .powerbi-container {
            height: calc(100vh - 120px);
            border: 3px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .header {
            padding: 15px 20px;
            background: linear-gradient(135deg, var(--sultana-azul) 0%, var(--sultana-azul-oscuro) 100%);
            border-bottom: 3px solid var(--sultana-naranja);
            color: white;
        }

        .demo-placeholder {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">

    {{-- HEADER SIMPLIFICADO --}}
    <div class="header">
        <div class="flex justify-between items-center">
            {{-- Botón Volver en lugar del logo --}}
            <button onclick="goBack()"
                    class="btn-volver text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Volver a Módulos</span>
            </button>

            {{-- Título centrado --}}
            <div class="text-center">
                <h1 class="text-xl font-bold">SISTEMA ANALÍTICO DE DATOS</h1>
                <p class="text-blue-100 text-sm">Tablero Power BI - {{ $moduloNombre }}</p>
            </div>

            {{-- Solo botón Cerrar Sesión --}}
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="btn-logout text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </div>

    {{-- CONTENEDOR POWER BI BORDEADO - SIMULACIÓN --}}
    <div class="powerbi-container">
        {{-- Placeholder de demostración --}}
        <div class="demo-placeholder">
            <div class="text-center">
                <i class="fas fa-chart-bar text-6xl mb-4"></i>
                <h2 class="text-3xl mb-2">TABLERO {{ strtoupper($moduloNombre) }}</h2>
                <p class="text-xl opacity-90">Power BI - En construcción</p>
                <p class="text-sm opacity-70 mt-4">Usuario: {{ $userName }}</p>
            </div>
        </div>

        {{-- Esto se activará cuando tengas las URLs reales --}}
        {{-- <iframe id="powerbi-iframe"
                src="{{ $powerbiUrl }}"
                frameborder="0"
                allowfullscreen
                class="w-full h-full">
        </iframe> --}}
    </div>

    <script>
        function goBack() {
            window.location.href = "{{ route('modules') }}";
        }
    </script>

</body>
</html>
