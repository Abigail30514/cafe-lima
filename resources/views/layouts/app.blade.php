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
        body {
            margin: 0;
            background: #f5f6f8;
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
            z-index: 1000;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            width: calc(100% - 250px);
            min-width: 0;
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

        @media (max-width: 991px) {
            .sidebar {
                width: 250px;
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                min-height: 100vh;
            }

            .main-content {
                margin-left: 250px;
                width: calc(100% - 250px);
                min-width: 0;
            }

            .app-wrapper {
                flex-direction: row;
            }

            .main-content .container-fluid {
                overflow-x: auto;
            }

            .system-title {
                font-size: 15px;
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

            <div class="d-flex align-items-center mb-4">

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

    <!-- Contenido principal -->

    <main class="main-content">

        <nav class="navbar navbar-light bg-white shadow-sm top-navbar">

            <div class="container-fluid px-4">

                <span class="navbar-brand system-title mb-0">
                    Sistema de Gestión de Disponibilidad Restaurante Café de Lima
                </span>

            </div>

        </nav>

        <div class="container-fluid px-4 py-4">

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

@stack('scripts')

</body>
</html>

@stack('scripts')

</body>
</html>