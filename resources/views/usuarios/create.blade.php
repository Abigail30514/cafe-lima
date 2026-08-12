@extends('layouts.app')

@section('title', 'Nuevo usuario')

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
        Nuevo usuario
    </h2>

    <p class="text-muted mb-0">
        Registra un nuevo usuario y asigna su rol.
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
            action="{{ route('usuarios.store') }}"
            method="POST"
        >
            @csrf

            <div class="row g-3">

                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
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
                        value="{{ old('email') }}"
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

                        <option value="">
                            Seleccione
                        </option>

                        <option
                            value="1"
                            @selected(old('rol') == 1)
                        >
                            Administrador
                        </option>

                        <option
                            value="2"
                            @selected(old('rol') == 2)
                        >
                            Cocina
                        </option>

                        <option
                            value="3"
                            @selected(old('rol') == 3)
                        >
                            Atención
                        </option>

                    </select>

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required
                    >

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Confirmar contraseña
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        required
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
                    <i class="bi bi-person-plus me-1"></i>
                    Registrar usuario
                </button>

            </div>

        </form>

    </div>

</div>

@endsection