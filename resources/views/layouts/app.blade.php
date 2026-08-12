<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistema Café de Lima')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        html,
        body {
            width: 100%;
            min-height: 100%;
        }

        body {
            margin: 0;
            background: #f5f6f8;
            overflow-x: hidden;
        }

        .app-wrapper {
            width: 100%;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #202529;

            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;

            display: flex;
            flex-direction: column;

            overflow-y: auto;

            z-index: 1045;

            transition:
                transform 0.3s ease,
                box-shadow 0.3s ease;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;

            width: calc(100% - 250px);
            min-width: 0;
            overflow-x: hidden;

            transition:
                margin-left 0.3s ease,
                width 0.3s ease;
        }


        /*
        |--------------------------------------------------------------------------
        | SIDEBAR OCULTA
        |--------------------------------------------------------------------------
        */

        body.sidebar-collapsed .sidebar {
            transform: translateX(-100%);
        }

        body.sidebar-collapsed .main-content {
            margin-left: 0;
            width: 100%;
        }


        /*
        |--------------------------------------------------------------------------
        | FONDO OSCURO PARA TABLET / MÓVIL
        |--------------------------------------------------------------------------
        */

        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.42);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        /*
        |--------------------------------------------------------------------------
        | BOTÓN PARA ABRIR / CERRAR SIDEBAR
        |--------------------------------------------------------------------------
        */

        .sidebar-toggle {
            width: 42px;
            height: 42px;

            border: 0;
            border-radius: 10px;

            background: #6f4e37;
            color: #ffffff;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 20px;

            transition: all 0.2s ease;
        }

        .sidebar-toggle:hover {
            background: #5c3f2d;
            transform: scale(1.04);
        }


        /*
        |--------------------------------------------------------------------------
        | BOTÓN INTERNO PARA OCULTAR
        |--------------------------------------------------------------------------
        */

        .sidebar-close {
            width: 34px;
            height: 34px;

            border: 0;
            border-radius: 8px;

            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;

            display: flex;
            align-items: center;
            justify-content: center;

            transition: background 0.2s ease;
        }

        .sidebar-close:hover {
            background: rgba(255, 255, 255, 0.16);
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 16px;
            margin: 3px 12px;
            border-radius: 9px;
            transition: all 0.2s ease;
            color: #f1f3f5 !important;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 17px;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.09);
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            background: #6f4e37;
            color: #ffffff !important;
            font-weight: 600;
        }

        .sidebar-logo {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #8b5e3c, #d9b38c);
            color: #ffffff;
            font-size: 20px;
            flex-shrink: 0;
        }

        .user-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            background: #ffffff;
            color: #6f4e37;
            font-size: 19px;
            flex-shrink: 0;
        }

        .sidebar-section-title {
            color: #7f8992;
            font-size: 11px;
            letter-spacing: 1px;
        }

        .sidebar-logout {
            margin-top: auto;
            padding: 20px 16px;
        }

        .top-navbar {
            min-height: 72px;
        }

        .system-title {
            font-size: 18px;
            font-weight: 500;
        }

        .content-wrapper {
            min-width: 0;
        }

        .main-content img,
        .main-content canvas,
        .main-content svg {
            max-width: 100%;
        }

        .main-content .table-responsive {
            width: 100%;
            -webkit-overflow-scrolling: touch;
        }

        .main-content .card {
            min-width: 0;
        }

        @media (max-width: 991.98px) {

            .sidebar {
                width: min(280px, 86vw);
                box-shadow: 5px 0 18px rgba(0, 0, 0, 0.18);
            }

            .main-content,
            body.sidebar-collapsed .main-content {
                margin-left: 0;
                width: 100%;
            }

            body:not(.sidebar-collapsed) .sidebar {
                transform: translateX(0);
            }

            body.sidebar-collapsed .sidebar {
                transform: translateX(-100%);
            }

            body:not(.sidebar-collapsed) .sidebar-backdrop {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }

            .system-title {
                font-size: 15px;
            }

            .top-navbar {
                min-height: 64px;
            }

            .sidebar .nav-link {
                min-height: 46px;
            }

            .btn,
            .form-control,
            .form-select {
                min-height: 42px;
            }
        }

        @media (max-width: 575.98px) {

            .system-title {
                font-size: 13px;
                line-height: 1.25;
            }

            .sidebar-toggle {
                width: 40px;
                height: 40px;
            }

            .top-navbar .container-fluid {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .content-wrapper {
                padding-left: 12px !important;
                padding-right: 12px !important;
                padding-top: 16px !important;
                padding-bottom: 20px !important;
            }

            .sidebar .nav-link {
                padding-top: 12px;
                padding-bottom: 12px;
            }
        }
    </style>

</head>

<body>

<div class="d-flex app-wrapper">

    <!-- Sidebar -->

    <aside class="sidebar text-white">

        <div class="px-3 pt-4 pb-3">

            <!-- Logo del sistema -->

            <div class="d-flex align-items-center justify-content-between mb-4">

                <div class="d-flex align-items-center">

                    <div
                        class="sidebar-logo rounded-circle d-flex align-items-center justify-content-center me-3"
                    >
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>

                    <div>
                        <h4 class="text-white fw-bold mb-0">
                            Café de Lima
                        </h4>

                        <small style="color: #bfc5ca;">
                            Gestión de productos
                        </small>
                    </div>

                </div>


                <button
                    type="button"
                    class="sidebar-close"
                    id="sidebarClose"
                    title="Ocultar menú"
                    aria-label="Ocultar menú"
                >
                    <i class="bi bi-chevron-left"></i>
                </button>

            </div>

            <!-- Datos del usuario -->

            <div class="user-card p-3 rounded-3">

                <div class="d-flex align-items-center">

                    <div
                        class="user-avatar rounded-circle d-flex align-items-center justify-content-center me-3"
                    >
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <div class="overflow-hidden">

                        <div
                            class="text-white fw-semibold text-truncate"
                            title="{{ Auth::user()->name }}"
                        >
                            {{ Auth::user()->name }}
                        </div>

                        <small
                            class="d-block text-truncate"
                            style="
                                color: #bfc5ca;
                                max-width: 145px;
                            "
                            title="{{ Auth::user()->email }}"
                        >
                            {{ Auth::user()->email }}
                        </small>

                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">

                    <span
                        class="badge rounded-pill"
                        style="background: #6f4e37;"
                    >
                        {{ Auth::user()->nombreRol() }}
                    </span>

                    <small class="text-success">
                        <i
                            class="bi bi-circle-fill me-1"
                            style="font-size: 7px;"
                        ></i>
                        En línea
                    </small>

                </div>

            </div>

        </div>

        <!-- Título navegación -->

        <div class="px-3 mb-2">

            <small class="sidebar-section-title text-uppercase fw-semibold">
                Navegación
            </small>

        </div>

        <!-- Menú -->

        <nav class="nav flex-column">

            <a
                href="{{ route('dashboard') }}"
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >
                <i class="bi bi-house-door"></i>
                Inicio
            </a>

            @if(Auth::user()->esAdministrador())

                <a
                    href="{{ route('categorias.index') }}"
                    class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-grid"></i>
                    Categorías
                </a>

                <a
                    href="{{ route('productos.index') }}"
                    class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-cup-hot"></i>
                    Productos
                </a>

            @endif

            <a
                href="{{ route('disponibilidad.index') }}"
                class="nav-link {{ request()->routeIs('disponibilidad.*') ? 'active' : '' }}"
            >
                <i class="bi bi-arrow-repeat"></i>
                Disponibilidad
            </a>

            @if(Auth::user()->esAdministrador() || Auth::user()->esCocina())

                <a
                    href="{{ route('consumos.index') }}"
                    class="nav-link {{ request()->routeIs('consumos.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-cart-check"></i>
                    Consumos
                </a>
                <a
                    href="{{ route('analisis-consumo.index') }}"
                    class="nav-link {{ request()->routeIs('analisis-consumo.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-graph-up-arrow"></i>
                    Análisis de consumo
                </a>
                <a
                    href="{{ route('riesgo-agotamiento.index') }}"
                    class="nav-link {{ request()->routeIs('riesgo-agotamiento.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-speedometer2"></i>
                    Riesgo de agotamiento
                </a>
                <a
                    href="{{ route('alertas-reposicion.index') }}"
                    class="nav-link {{ request()->routeIs('alertas-reposicion.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-bell-fill"></i>
                    Alertas de reposición
                </a>
                <a
                    href="{{ route('historial.index') }}"
                    class="nav-link {{ request()->routeIs('historial.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-clock-history"></i>
                    Historial
                </a>

            @endif

            @if(Auth::user()->esAdministrador())

                <a
                    href="{{ route('reportes.index') }}"
                    class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    Reportes
                </a>

                <a
                    href="{{ route('usuarios.index') }}"
                    class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-people"></i>
                    Usuarios
                </a>

            @endif

        </nav>

        <!-- Cerrar sesión -->

        <div class="sidebar-logout">

            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="btn btn-danger w-100"
                >
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Cerrar sesión
                </button>

            </form>

        </div>

    </aside>

    <div
        class="sidebar-backdrop"
        id="sidebarBackdrop"
        aria-hidden="true"
    ></div>

    <!-- Contenido principal -->

    <main class="main-content">

        <nav class="navbar navbar-light bg-white shadow-sm top-navbar">

        <div class="container-fluid px-3 px-md-4">

            <div class="d-flex align-items-center gap-3 w-100">

                <button
                    type="button"
                    class="sidebar-toggle"
                    id="sidebarToggle"
                    title="Mostrar u ocultar menú"
                    aria-label="Mostrar u ocultar menú"
                >
                    <i class="bi bi-list"></i>
                </button>


                <span class="navbar-brand system-title mb-0 text-truncate">

                    Sistema de Gestión de Disponibilidad Restaurante Café de Lima

                </span>

            </div>

        </div>

        </nav>

        <div class="container-fluid px-3 px-md-4 py-3 py-md-4 content-wrapper">

            @yield('content')

        </div>

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | MENSAJES DE ÉXITO
    |--------------------------------------------------------------------------
    */

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Operación realizada',
            text: @json(session('success')),
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#6f4e37',
            timer: 2500,
            timerProgressBar: true
        });
    @endif

    /*
    |--------------------------------------------------------------------------
    | MENSAJES DE ERROR
    |--------------------------------------------------------------------------
    */

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'No se pudo realizar',
            text: @json(session('error')),
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#dc3545'
        });
    @endif

    /*
    |--------------------------------------------------------------------------
    | CONFIRMACIÓN PARA ELIMINAR
    |--------------------------------------------------------------------------
    */

    const formulariosEliminar = document.querySelectorAll(
        '.formulario-eliminar'
    );

    formulariosEliminar.forEach(function (formulario) {

        formulario.addEventListener('submit', function (event) {
            event.preventDefault();

            const nombre = formulario.dataset.nombre || 'este registro';

            Swal.fire({
                icon: 'warning',
                title: '¿Deseas eliminarlo?',
                text: 'Se eliminará ' + nombre + '. Esta acción no se puede deshacer.',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                focusCancel: true
            }).then(function (resultado) {

                if (resultado.isConfirmed) {
                    formulario.submit();
                }

            });
        });

    });

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const body = document.body;
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const enlacesSidebar = document.querySelectorAll('.sidebar .nav-link');

    const BREAKPOINT = 992;

    function esMovilOTablet() {
        return window.innerWidth < BREAKPOINT;
    }

    function aplicarEstadoInicial() {

        if (esMovilOTablet()) {
            // En tablet y móvil el menú inicia cerrado.
            body.classList.add('sidebar-collapsed');
        } else {
            // En PC se respeta la preferencia guardada.
            const sidebarGuardado = localStorage.getItem('cafeLimaSidebar');

            if (sidebarGuardado === 'collapsed') {
                body.classList.add('sidebar-collapsed');
            } else {
                body.classList.remove('sidebar-collapsed');
            }
        }
    }

    function abrirSidebar() {
        body.classList.remove('sidebar-collapsed');

        if (!esMovilOTablet()) {
            localStorage.setItem('cafeLimaSidebar', 'expanded');
        }
    }

    function cerrarSidebar() {
        body.classList.add('sidebar-collapsed');

        if (!esMovilOTablet()) {
            localStorage.setItem('cafeLimaSidebar', 'collapsed');
        }
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            if (body.classList.contains('sidebar-collapsed')) {
                abrirSidebar();
            } else {
                cerrarSidebar();
            }
        });
    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', cerrarSidebar);
    }

    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', cerrarSidebar);
    }

    enlacesSidebar.forEach(function (enlace) {
        enlace.addEventListener('click', function () {
            if (esMovilOTablet()) {
                cerrarSidebar();
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (
            event.key === 'Escape' &&
            !body.classList.contains('sidebar-collapsed')
        ) {
            cerrarSidebar();
        }
    });

    let anchoAnterior = window.innerWidth;

    window.addEventListener('resize', function () {
        const cruzoBreakpoint =
            (anchoAnterior < BREAKPOINT && window.innerWidth >= BREAKPOINT) ||
            (anchoAnterior >= BREAKPOINT && window.innerWidth < BREAKPOINT);

        if (cruzoBreakpoint) {
            aplicarEstadoInicial();
        }

        anchoAnterior = window.innerWidth;
    });

    aplicarEstadoInicial();
});
</script>

@stack('scripts')

</body>
</html>