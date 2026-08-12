@extends('layouts.app')

@section('title','Usuarios')

@section('content')

<style>

    .user-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
    }

    .user-mobile-item {
        padding: 16px;
        border-bottom: 1px solid #eceff2;
    }

    .user-mobile-item:last-child {
        border-bottom: 0;
    }

    .user-mobile-title {
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.25;
    }

    .user-mobile-meta {
        font-size: 0.875rem;
        color: #6c757d;
        word-break: break-word;
    }

    @media (max-width: 767.98px) {

        .user-header {
            flex-direction: column;
            align-items: stretch !important;
            gap: 14px;
            margin-bottom: 20px !important;
        }

        .user-header h2 {
            font-size: 1.55rem;
            margin-bottom: 4px;
        }

        .user-header p {
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        .user-header .btn {
            width: 100%;
        }

        .user-card {
            overflow: hidden;
        }

        .user-mobile-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 14px;
        }

        .user-mobile-actions .btn {
            width: 100%;
            min-height: 42px;
        }
    }

</style>


{{-- ENCABEZADO --}}

<div class="d-flex justify-content-between align-items-center mb-4 user-header">

    <div>

        <h2 class="fw-bold">
            Gestión de usuarios
        </h2>

        <p class="text-muted">
            Administración de usuarios del sistema.
        </p>

    </div>

    <a
        href="{{ route('usuarios.create') }}"
        class="btn btn-primary"
    >
        <i class="bi bi-person-plus me-1"></i>
        Nuevo usuario
    </a>

</div>


{{-- LISTADO --}}

<div class="card user-card">

    {{-- ============================================================ --}}
    {{-- TABLET / PC --}}
    {{-- ============================================================ --}}

    <div class="card-body p-0 d-none d-md-block">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">

                    <tr>

                        <th class="ps-4">ID</th>

                        <th>Nombre</th>

                        <th>Correo</th>

                        <th>Rol</th>

                        <th class="text-center">Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($usuarios as $usuario)

                        <tr>

                            <td class="ps-4">
                                {{ $usuario->id }}
                            </td>

                            <td class="fw-semibold">
                                {{ $usuario->name }}
                            </td>

                            <td>
                                {{ $usuario->email }}
                            </td>

                            <td>

                                @if($usuario->rol==1)

                                    <span class="badge bg-danger">
                                        Administrador
                                    </span>

                                @elseif($usuario->rol==2)

                                    <span class="badge bg-success">
                                        Cocina
                                    </span>

                                @else

                                    <span class="badge bg-primary">
                                        Atención
                                    </span>

                                @endif

                            </td>

                            <td class="text-center">

                                <a
                                    href="{{ route('usuarios.edit',$usuario) }}"
                                    class="btn btn-warning btn-sm"
                                    title="Editar usuario"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form
                                    action="{{ route('usuarios.destroy', $usuario) }}"
                                    method="POST"
                                    class="d-inline formulario-eliminar"
                                    data-nombre="al usuario {{ $usuario->name }}"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        title="Eliminar usuario"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-5"
                            >

                                <i class="bi bi-people fs-1 text-muted"></i>

                                <h6 class="fw-bold mt-3">
                                    No existen usuarios registrados
                                </h6>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ============================================================ --}}
    {{-- MÓVIL --}}
    {{-- ============================================================ --}}

    <div class="d-md-none">

        @forelse($usuarios as $usuario)

            <div class="user-mobile-item">

                <div class="d-flex justify-content-between align-items-start gap-3">

                    <div>

                        <div class="user-mobile-title">
                            {{ $usuario->name }}
                        </div>

                        <div class="user-mobile-meta mt-1">
                            <i class="bi bi-envelope me-1"></i>
                            {{ $usuario->email }}
                        </div>

                    </div>

                    <div class="text-end">

                        @if($usuario->rol==1)

                            <span class="badge bg-danger">
                                Administrador
                            </span>

                        @elseif($usuario->rol==2)

                            <span class="badge bg-success">
                                Cocina
                            </span>

                        @else

                            <span class="badge bg-primary">
                                Atención
                            </span>

                        @endif

                    </div>

                </div>


                <div class="user-mobile-meta mt-2">
                    ID: {{ $usuario->id }}
                </div>


                <div class="user-mobile-actions">

                    <a
                        href="{{ route('usuarios.edit',$usuario) }}"
                        class="btn btn-warning btn-sm"
                    >
                        <i class="bi bi-pencil me-1"></i>
                        Editar
                    </a>

                    <form
                        action="{{ route('usuarios.destroy', $usuario) }}"
                        method="POST"
                        class="formulario-eliminar"
                        data-nombre="al usuario {{ $usuario->name }}"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-danger btn-sm"
                        >
                            <i class="bi bi-trash me-1"></i>
                            Eliminar
                        </button>

                    </form>

                </div>

            </div>

        @empty

            <div class="text-center py-5 px-3">

                <i class="bi bi-people fs-1 text-muted"></i>

                <h6 class="fw-bold mt-3">
                    No existen usuarios registrados
                </h6>

            </div>

        @endforelse

    </div>

</div>

@endsection