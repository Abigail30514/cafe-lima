@extends('layouts.app')

@section('title', 'Nuevo usuario')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold mb-1">Nuevo usuario</h2>
    <p class="text-muted mb-0">
        Registra un nuevo usuario y asigna su rol.
    </p>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correo</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rol</label>

                    <select name="rol" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="1" @selected(old('rol') == 1)>
                            Administrador
                        </option>
                        <option value="2" @selected(old('rol') == 2)>
                            Cocina
                        </option>
                        <option value="3" @selected(old('rol') == 3)>
                            Atención
                        </option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirmar contraseña</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        required
                    >
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a
                    href="{{ route('usuarios.index') }}"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-person-plus me-1"></i>
                    Registrar usuario
                </button>
            </div>

        </form>

    </div>
</div>

@endsection