@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')

<style>

    .user-form-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
    }

    @media (max-width: 767.98px) {

        .user-form-header {
            margin-bottom: 20px !important;
        }

        .user-form-header h2 {
            font-size: 1.55rem;
        }

        .user-form-header p {
            font-size: 0.95rem;
        }

        .user-form-card .card-body {
            padding: 1rem !important;
        }

        .user-form-actions {
            display: grid !important;
            grid-template-columns: 1fr;
            gap: 8px !important;
        }

        .user-form-actions .btn {
            width: 100%;
            min-height: 44px;
        }
    }

</style>


<div class="mb-4 user-form-header">

    <h2 class="fw-bold mb-1">
        Editar usuario
    </h2>

    <p class="text-muted mb-0">
        Actualiza los datos, el rol o la contraseña.
    </p>

</div>


@if($errors->any())

    <div class="alert alert-danger">

        <div class="fw-semibold mb-2">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Revisa la información ingresada:
        </div>

        <ul class="mb-0">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


<div class="card user-form-card">

    <div class="card-body p-4">

        <form
            action="{{ route('usuarios.update', $usuario) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $usuario->name) }}"
                        required
                    >

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Correo
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $usuario->email) }}"
                        required
                    >

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Rol
                    </label>

                    <select
                        name="rol"
                        class="form-select"
                        required
                    >

                        <option
                            value="1"
                            @selected(old('rol', $usuario->rol) == 1)
                        >
                            Administrador
                        </option>

                        <option
                            value="2"
                            @selected(old('rol', $usuario->rol) == 2)
                        >
                            Cocina
                        </option>

                        <option
                            value="3"
                            @selected(old('rol', $usuario->rol) == 3)
                        >
                            Atención
                        </option>

                    </select>

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Nueva contraseña
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                    >

                    <small class="text-muted">
                        Déjala vacía para mantener la contraseña actual.
                    </small>

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Confirmar nueva contraseña
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                    >

                </div>

            </div>


            <div class="d-flex justify-content-end gap-2 mt-4 user-form-actions">

                <a
                    href="{{ route('usuarios.index') }}"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-save me-1"></i>
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>

@endsection