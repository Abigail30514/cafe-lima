@extends('layouts.app')

@section('title', 'Categorías')

@section('content')

<style>

    .category-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
    }

    .category-mobile-item {
        padding: 16px;
        border-bottom: 1px solid #eceff2;
    }

    .category-mobile-item:last-child {
        border-bottom: 0;
    }

    .category-mobile-title {
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.25;
    }

    .category-mobile-meta {
        font-size: 0.875rem;
        color: #6c757d;
    }

    @media (max-width: 767.98px) {

        .category-header {
            flex-direction: column;
            align-items: stretch !important;
            gap: 14px;
            margin-bottom: 20px !important;
        }

        .category-header h2 {
            font-size: 1.55rem;
            margin-bottom: 0;
        }

        .category-header .btn {
            width: 100%;
        }

        .category-card {
            overflow: hidden;
        }

        .category-mobile-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 14px;
        }

        .category-mobile-actions .btn {
            width: 100%;
            min-height: 42px;
        }

        .modal-dialog {
            margin: 0.75rem;
        }

        .modal-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .modal-footer > * {
            margin: 0 !important;
            width: 100%;
        }
    }

</style>


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


{{-- ENCABEZADO --}}

<div class="d-flex justify-content-between align-items-center mb-4 category-header">

    <div>

        <h2 class="fw-bold mb-1">
            Categorías
        </h2>

        <p class="text-muted mb-0">
            Administra las categorías utilizadas para organizar los productos.
        </p>

    </div>

    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalCrearCategoria"
    >
        <i class="bi bi-plus-circle me-1"></i>
        Nueva categoría
    </button>

</div>


{{-- LISTADO --}}

<div class="card category-card">

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
                        <th width="180" class="text-center">
                            Acciones
                        </th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($categorias as $categoria)

                        <tr>

                            <td class="ps-4">
                                {{ $categoria->id }}
                            </td>

                            <td class="fw-semibold">
                                {{ $categoria->nombre }}
                            </td>

                            <td class="text-center">

                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarCategoria{{ $categoria->id }}"
                                    title="Editar categoría"
                                >
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form
                                    action="{{ route('categorias.destroy', $categoria) }}"
                                    method="POST"
                                    class="d-inline formulario-eliminar"
                                    data-nombre="la categoría {{ $categoria->nombre }}"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        title="Eliminar categoría"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="3"
                                class="text-center py-5"
                            >

                                <i class="bi bi-tags fs-1 text-muted"></i>

                                <h6 class="fw-bold mt-3">
                                    No existen categorías registradas
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

        @forelse($categorias as $categoria)

            <div class="category-mobile-item">

                <div class="d-flex justify-content-between align-items-start gap-3">

                    <div>

                        <div class="category-mobile-title">
                            {{ $categoria->nombre }}
                        </div>

                        <div class="category-mobile-meta mt-1">
                            ID: {{ $categoria->id }}
                        </div>

                    </div>

                    <span class="badge bg-primary-subtle text-primary">
                        <i class="bi bi-tag me-1"></i>
                        Categoría
                    </span>

                </div>


                <div class="category-mobile-actions">

                    <button
                        type="button"
                        class="btn btn-warning btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditarCategoria{{ $categoria->id }}"
                    >
                        <i class="bi bi-pencil me-1"></i>
                        Editar
                    </button>

                    <form
                        action="{{ route('categorias.destroy', $categoria) }}"
                        method="POST"
                        class="formulario-eliminar"
                        data-nombre="la categoría {{ $categoria->nombre }}"
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

                <i class="bi bi-tags fs-1 text-muted"></i>

                <h6 class="fw-bold mt-3">
                    No existen categorías registradas
                </h6>

            </div>

        @endforelse

    </div>

</div>


{{-- ============================================================ --}}
{{-- MODALES PARA EDITAR CATEGORÍAS --}}
{{-- ============================================================ --}}

@foreach($categorias as $categoria)

    <div
        class="modal fade"
        id="modalEditarCategoria{{ $categoria->id }}"
        tabindex="-1"
        aria-labelledby="tituloEditarCategoria{{ $categoria->id }}"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form
                    action="{{ route('categorias.update', $categoria) }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    <div class="modal-header">

                        <h5
                            class="modal-title"
                            id="tituloEditarCategoria{{ $categoria->id }}"
                        >
                            <i class="bi bi-pencil-square me-1"></i>
                            Editar categoría
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar"
                        ></button>

                    </div>

                    <div class="modal-body">

                        <label class="form-label">
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            value="{{ $categoria->nombre }}"
                            required
                            maxlength="100"
                        >

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancelar
                        </button>

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

    </div>

@endforeach


{{-- ============================================================ --}}
{{-- MODAL PARA CREAR CATEGORÍA --}}
{{-- ============================================================ --}}

<div
    class="modal fade"
    id="modalCrearCategoria"
    tabindex="-1"
    aria-labelledby="tituloCrearCategoria"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form
                action="{{ route('categorias.store') }}"
                method="POST"
            >
                @csrf

                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="tituloCrearCategoria"
                    >
                        <i class="bi bi-plus-circle me-1"></i>
                        Nueva categoría
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>

                <div class="modal-body">

                    <label class="form-label">
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        class="form-control"
                        value="{{ old('nombre') }}"
                        required
                        maxlength="100"
                    >

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-circle me-1"></i>
                        Registrar
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection