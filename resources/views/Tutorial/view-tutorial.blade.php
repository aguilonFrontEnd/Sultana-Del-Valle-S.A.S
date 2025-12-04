{{-- resources/views/Tutorial/view-tutorial.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorial - {{ $moduloNombre }} | Sultana del Valle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --sultana-azul: #0A35FF;
            --sultana-azul-oscuro: #0830E0;
            --sultana-naranja: #FF7A00;
            --sultana-gris: #F4F6F8;
            --sultana-texto: #0F172A;
        }

        .bg-sultana-azul {
            background: linear-gradient(135deg, var(--sultana-azul) 0%, var(--sultana-azul-oscuro) 100%);
        }

        .text-sultana-azul {
            color: var(--sultana-azul);
        }

        body {
            background-color: white;
            color: var(--sultana-texto);
        }

        /* WRAPPER DEL HEADER FIJO (azul + barra gris) */
        .header-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 40;
        }

        .header {
            padding: 15px 20px;
            background: linear-gradient(135deg, var(--sultana-azul) 0%, var(--sultana-azul-oscuro) 100%);
            border-bottom: 3px solid var(--sultana-naranja);
            color: white;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .title-section {
            flex: 1;
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

        /* BOTÓN IR AL MÓDULO — versión difuminada / elegante */
        .btn-ir-modulo {
            position: relative;
            overflow: hidden;
            padding: 0.65rem 2rem;
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.65);
            background: linear-gradient(135deg,
                rgba(255, 255, 255, 0.24),
                rgba(255, 255, 255, 0.05)
            );
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            box-shadow:
                0 12px 30px rgba(0, 0, 0, 0.22),
                0 0 0 1px rgba(15, 23, 42, 0.10);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .btn-ir-modulo::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 0% 0%,
                rgba(255, 255, 255, 0.55),
                transparent 55%);
            opacity: 0.9;
            mix-blend-mode: screen;
            pointer-events: none;
        }

        .btn-ir-modulo span,
        .btn-ir-modulo i {
            position: relative;
            z-index: 1;
        }

        .btn-ir-modulo i {
            width: 22px;
            height: 22px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #FFFFFF, #FFE7C2);
            color: var(--sultana-azul);
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.25);
        }

        .btn-ir-modulo:hover {
            transform: translateY(-1px) translateX(-0.5px);
            box-shadow:
                0 16px 40px rgba(0, 0, 0, 0.35),
                0 0 0 1px rgba(255, 255, 255, 0.85);
            background: linear-gradient(135deg,
                rgba(255, 255, 255, 0.32),
                rgba(255, 255, 255, 0.08)
            );
        }

        .btn-ir-modulo:active {
            transform: translateY(0);
            box-shadow:
                0 8px 20px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.75);
        }

        /* Para que el contenido no se esconda bajo el header fijo */
        .tutorial-wrapper {
            padding-top: 140px; /* alto header + barra gris aprox */
        }

        .tutorial-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1.5rem 2.5rem;
        }

        .card {
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -8px rgba(15, 23, 42, 0.18);
            padding: 1.75rem 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .card-icon {
            width: 34px;
            height: 34px;
            border-radius: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0A35FF 0%, #FF7A00 100%);
            color: #ffffff;
        }

        .tag-modulo {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.7rem;
            border-radius: 999px;
            font-size: 0.75rem;
            background: #e0edff;
            color: #0A35FF;
            font-weight: 600;
        }

        .steps-list li::marker {
            color: #0A35FF;
        }

        .tip-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.25rem 0.7rem;
            border-radius: 999px;
            background: #ecfdf3;
            color: #166534;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .gallery-item {
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            background: #0b1120;
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
        }
    </style>
</head>
<body class="min-h-screen">

    {{-- HEADER FIJO (AZUL + BARRA GRIS) --}}
    <div class="header-wrapper">
        <div class="header">
            <div class="header-content">
                <div class="title-section">
                    <h1 class="text-2xl font-bold">SISTEMA DE TABLEROS ESTADÍSTICOS</h1>
                    <h2 class="text-lg opacity-90">Transportes Sultana del Valle S.A.S</h2>
                </div>

                {{-- ÚNICO BOTÓN: IR AL MÓDULO --}}
                <button
                    type="button"
                    class="btn-ir-modulo"
                    onclick="window.location.href='{{ route('tablero.show', ['modulo' => $moduloId]) }}'">
                    <i class="fas fa-chart-bar"></i>
                    <span>IR AL DASHBOARD DE DATOS...</span>
                </button>
            </div>
        </div>

        <div class="info-bar">
            <div id="date-time">Cargando fecha y hora...</div>
            <div class="font-semibold text-sultana-azul">
                Bienvenido al área de {{ $userArea }}
            </div>
        </div>
    </div>

    {{-- CONTENIDO SCROLLEABLE DEL TUTORIAL --}}
    <div class="tutorial-wrapper">
        <main class="tutorial-container">

            {{-- CHIP DEL MÓDULO --}}
            <div class="mb-4">
                <span class="tag-modulo">
                    <i class="fas fa-chart-line"></i>
                    Tablero: {{ $moduloNombre }}
                </span>
            </div>

            {{-- CARD 1: INTRO / QUÉ VAS A VER --}}
            <section class="card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-info"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">
                            ¿Qué vas a ver en este tablero?
                        </h2>
                        <p class="text-xs text-slate-500">
                            Explicación rápida antes de entrar al tablero de Power BI de {{ $moduloNombre }}.
                        </p>
                    </div>
                </div>

                <p class="text-sm text-slate-700 mb-2">
                    {{ $tutorial['intro'] }}
                </p>

                <ul class="list-disc list-inside text-sm text-slate-700 space-y-1 mt-2">
                    @foreach($tutorial['kpis'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </section>

            {{-- CARD 2: CÓMO NAVEGAR --}}
            <section class="card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-mouse-pointer"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">
                            ¿Cómo navegar dentro del tablero?
                        </h2>
                    </div>
                </div>

                <ol class="steps-list list-decimal list-inside text-sm text-slate-700 space-y-1">
                    @foreach($tutorial['steps'] as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ol>
            </section>

            {{-- CARD 3: RECOMENDACIONES --}}
            <section class="card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">
                            Recomendaciones de uso
                        </h2>
                    </div>
                </div>

                <p class="text-sm text-slate-700 mb-3">
                    Ten en cuenta estas recomendaciones para interpretar correctamente la información:
                </p>

                <ul class="list-disc list-inside text-sm text-slate-700 space-y-1">
                    @foreach($tutorial['recomendaciones'] as $rec)
                        <li>{{ $rec }}</li>
                    @endforeach
                </ul>

                <div class="mt-3">
                    <span class="tip-pill">
                        <i class="fas fa-check-circle"></i>
                        Puedes volver a este tutorial cuando quieras desde el icono de ayuda en la vista de módulos.
                    </span>
                </div>
            </section>

            {{-- CARD 4: GALERÍA DE IMÁGENES (OPCIONAL) --}}
            @if(!empty($tutorial['imagenes']))
                <section class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">
                                Ejemplos visuales del tablero
                            </h2>
                            <p class="text-xs text-slate-500">
                                Usa estas imágenes como guía para ubicarte dentro del tablero.
                            </p>
                        </div>
                    </div>

                    <div class="gallery-grid">
                        @foreach($tutorial['imagenes'] as $img)
                            <div class="gallery-item">
                                <img src="{{ asset($img) }}" alt="Tutorial {{ $moduloNombre }}">
                            </div>
                        @endforeach
                    </div>

                    <p class="text-[11px] text-slate-500 mt-2">
                        Tip: puedes hacer zoom en el navegador (Ctrl + + / Ctrl + -) para ver más detalle.
                    </p>
                </section>
            @endif

        </main>
    </div>

    <script>
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

        document.addEventListener('DOMContentLoaded', () => {
            updateDateTime();
            setInterval(updateDateTime, 1000);
        });
    </script>

</body>
</html>
