@extends('layouts.app')

@section('title','Usuarios')

@section('content')



<div class="d-flex justify-content-between align-items-center mb-4">

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
        <i class="bi bi-person-plus"></i>

        Nuevo usuario
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-dark">

            <tr>

                <th>ID</th>

                <th>Nombre</th>

                <th>Correo</th>

                <th>Rol</th>

                <th>Acciones</th>

            </tr>

            </thead>

            <tbody>

            @forelse($usuarios as $usuario)

                <tr>

                    <td>{{ $usuario->id }}</td>

                    <td>{{ $usuario->name }}</td>

                    <td>{{ $usuario->email }}</td>

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

                    <td>

                        <a
                            href="{{ route('usuarios.edit',$usuario) }}"
                            class="btn btn-warning btn-sm"
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

                        No existen usuarios registrados.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection