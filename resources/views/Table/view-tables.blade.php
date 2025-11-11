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

        .powerbi-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            background: #f8fafc;
        }

        .loading-spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid var(--sultana-azul);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

        .error-container {
            background: #fef2f2;
            color: #dc2626;
            padding: 40px;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">

    {{-- HEADER --}}
    <div class="header">
        <div class="flex justify-between items-center">
            {{-- Botón Volver --}}
            <button onclick="goBack()"
                    class="btn-volver text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Volver a Módulos</span>
            </button>

            {{-- Título centrado --}}
            <div class="text-center">
                <h1 class="text-xl font-bold">SISTEMA ANALÍTICO DE DATOS</h1>
                <p class="text-blue-100 text-sm">Tablero Power BI - {{ $moduloNombre }}</p>
                <p class="text-blue-200 text-xs">Usuario: {{ $userName }} | Área: {{ $userArea }}</p>
            </div>

            {{-- Botón Cerrar Sesión --}}
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

    {{-- CONTENEDOR POWER BI --}}
    <div class="powerbi-container">
        @if($powerbiUrl && $powerbiUrl !== '#')
            {{-- Iframe real de Power BI --}}
            <iframe id="powerbi-iframe"
                    src="{{ $powerbiUrl }}"
                    frameborder="0"
                    allowfullscreen
                    class="powerbi-iframe"
                    onload="hideLoader()"
                    onerror="showError()">
            </iframe>
            
            {{-- Loader --}}
            <div id="loader" class="loading-container">
                <div class="loading-spinner"></div>
                <p class="text-gray-600">Cargando tablero de Power BI...</p>
                <p class="text-gray-500 text-sm mt-2">Módulo: {{ $moduloNombre }}</p>
            </div>

            {{-- Error message --}}
            <div id="error-message" class="error-container" style="display: none;">
                <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                <h2 class="text-2xl font-bold mb-2">Error al cargar el tablero</h2>
                <p class="mb-4">No se pudo cargar el tablero de Power BI para el módulo {{ $moduloNombre }}</p>
                <button onclick="reloadIframe()" class="bg-red-600 text-white px-4 py-2 rounded-lg">
                    Reintentar
                </button>
            </div>
        @else
            {{-- Placeholder para URLs no configuradas --}}
            <div class="demo-placeholder">
                <div class="text-center">
                    <i class="fas fa-chart-bar text-6xl mb-4"></i>
                    <h2 class="text-3xl mb-2">TABLERO {{ strtoupper($moduloNombre) }}</h2>
                    <p class="text-xl opacity-90">Power BI - En construcción</p>
                    <p class="text-sm opacity-70 mt-4">
                        URL de Power BI no configurada para este módulo<br>
                        Contacta al administrador para configurar el tablero
                    </p>
                </div>
            </div>
        @endif
    </div>

    <script>
        function goBack() {
            window.location.href = "{{ route('modules') }}";
        }

        function hideLoader() {
            const loader = document.getElementById('loader');
            if (loader) loader.style.display = 'none';
            
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) errorMessage.style.display = 'none';
        }

        function showError() {
            const loader = document.getElementById('loader');
            if (loader) loader.style.display = 'none';
            
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) errorMessage.style.display = 'flex';
        }

        function reloadIframe() {
            const loader = document.getElementById('loader');
            const iframe = document.getElementById('powerbi-iframe');
            const errorMessage = document.getElementById('error-message');
            
            if (loader) loader.style.display = 'flex';
            if (errorMessage) errorMessage.style.display = 'none';
            if (iframe) iframe.src = iframe.src;
        }

        // Ocultar loader después de 10 segundos como máximo
        setTimeout(hideLoader, 10000);

        // Ocultar loader automáticamente cuando el iframe carga
        document.addEventListener('DOMContentLoaded', function() {
            const iframe = document.getElementById('powerbi-iframe');
            if (iframe) {
                iframe.onload = hideLoader;
            }
        });
    </script>

</body>
</html>