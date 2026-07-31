@extends('layouts.app')

@section('title', 'Productos')

@section('content')

<style>
    .product-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
    }

    .status-buttons .btn {
        min-width: 35px;
    }

    .product-status {
        min-width: 95px;
        display: inline-block;
        text-align: center;
    }

    .actions-column {
        white-space: nowrap;
    }
</style>

{{-- Errores de validación --}}
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

{{-- Encabezado --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Productos
        </h2>

        <p class="text-muted mb-0">
            Administra los productos y su disponibilidad.
        </p>
    </div>

    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalCrearProducto"
    >
        <i class="bi bi-plus-circle me-1"></i>
        Nuevo producto
    </button>

</div>

{{-- Filtros --}}
<div class="card product-card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('productos.index') }}"
        >
            <div class="row g-3 align-items-end">

                <div class="col-md-4">

                    <label class="form-label">
                        Buscar producto
                    </label>

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Ejemplo: Cappuccino"
                        value="{{ request('buscar') }}"
                    >

                </div>

                <div class="col-md-3">

                    <label class="form-label">
                        Categoría
                    </label>

                    <select
                        name="categoria"
                        class="form-select"
                    >
                        <option value="">
                            Todas las categorías
                        </option>

                        @foreach($categorias as $categoria)

                            <option
                                value="{{ $categoria->id }}"
                                @selected(
                                    request('categoria') == $categoria->id
                                )
                            >
                                {{ $categoria->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-3">

                    <label class="form-label">
                        Estado
                    </label>

                    <select
                        name="estado"
                        class="form-select"
                    >
                        <option value="">
                            Todos los estados
                        </option>

                        <option
                            value="1"
                            @selected(request('estado') == '1')
                        >
                            Disponible
                        </option>

                        <option
                            value="2"
                            @selected(request('estado') == '2')
                        >
                            Bajo stock
                        </option>

                        <option
                            value="3"
                            @selected(request('estado') == '3')
                        >
                            Agotado
                        </option>

                    </select>

                </div>

                <div class="col-md-2 d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-search me-1"></i>
                        Buscar
                    </button>

                    <a
                        href="{{ route('productos.index') }}"
                        class="btn btn-secondary"
                        title="Limpiar filtros"
                    >
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>

                </div>

            </div>
        </form>

    </div>

</div>

{{-- Tabla de productos --}}
<div class="card product-card">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">

                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th class="text-center">Recomendado</th>
                        <th>Observación</th>
                        <th class="text-center">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($productos as $producto)

                        <tr>

                            <td class="ps-4">
                                {{ $producto->id }}
                            </td>

                            <td class="fw-semibold">
                                {{ $producto->nombre }}
                            </td>

                            <td>
                                {{ $producto->category->nombre ?? 'Sin categoría' }}
                            </td>

                            {{-- Estado y cambio rápido --}}
                            <td>

                                <div class="mb-2">

                                    @if($producto->estado == 1)

                                        <span class="badge bg-success product-status">
                                            Disponible
                                        </span>

                                    @elseif($producto->estado == 2)

                                        <span class="badge bg-warning text-dark product-status">
                                            Bajo stock
                                        </span>

                                    @else

                                        <span class="badge bg-danger product-status">
                                            Agotado
                                        </span>

                                    @endif

                                </div>

                                <form
                                    action="{{ route('productos.estado', $producto) }}"
                                    method="POST"
                                    class="d-flex gap-1 status-buttons"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        name="estado"
                                        value="1"
                                        class="btn btn-success btn-sm"
                                        title="Marcar como disponible"
                                        @disabled($producto->estado == 1)
                                    >
                                        <i class="bi bi-check-circle"></i>
                                    </button>

                                    <button
                                        type="submit"
                                        name="estado"
                                        value="2"
                                        class="btn btn-warning btn-sm"
                                        title="Marcar como bajo stock"
                                        @disabled($producto->estado == 2)
                                    >
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </button>

                                    <button
                                        type="submit"
                                        name="estado"
                                        value="3"
                                        class="btn btn-danger btn-sm"
                                        title="Marcar como agotado"
                                        @disabled($producto->estado == 3)
                                    >
                                        <i class="bi bi-x-circle"></i>
                                    </button>

                                </form>

                            </td>



                            <td class="text-center">

                                @if($producto->destacado)

                                    <span class="badge bg-warning text-dark">

                                        <i class="bi bi-star-fill me-1"></i>

                                        Recomendado

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        No

                                    </span>

                                @endif

                            </td>

                            <td>

                                {{ $producto->observacion ?: 'Sin observación' }}

                            </td>

                            {{-- Acciones --}}
                            <td class="text-center actions-column">

                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarProducto{{ $producto->id }}"
                                    title="Editar producto"
                                >
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form
                                    action="{{ route('productos.destroy', $producto) }}"
                                    method="POST"
                                    class="d-inline formulario-eliminar"
                                    data-nombre="el producto {{ $producto->nombre }}"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        title="Eliminar producto"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >
                                <i class="bi bi-box-seam fs-1 text-muted"></i>

                                <h6 class="fw-bold mt-3">
                                    No se encontraron productos
                                </h6>

                                <p class="text-muted mb-0">
                                    Registra un producto o modifica los filtros.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- Modales para editar productos --}}
@foreach($productos as $producto)

    <div
        class="modal fade"
        id="modalEditarProducto{{ $producto->id }}"
        tabindex="-1"
        aria-labelledby="tituloEditarProducto{{ $producto->id }}"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form
                    action="{{ route('productos.update', $producto) }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    <div class="modal-header">

                        <h5
                            class="modal-title"
                            id="tituloEditarProducto{{ $producto->id }}"
                        >
                            <i class="bi bi-pencil-square me-1"></i>
                            Editar producto
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar"
                        ></button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Nombre
                            </label>

                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                value="{{ $producto->nombre }}"
                                maxlength="150"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Categoría
                            </label>

                            <select
                                name="category_id"
                                class="form-select"
                                required
                            >

                                @foreach($categorias as $categoria)

                                    <option
                                        value="{{ $categoria->id }}"
                                        @selected(
                                            $producto->category_id == $categoria->id
                                        )
                                    >
                                        {{ $categoria->nombre }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Estado
                            </label>

                            <select
                                name="estado"
                                class="form-select"
                                required
                            >

                                <option
                                    value="1"
                                    @selected($producto->estado == 1)
                                >
                                    Disponible
                                </option>

                                <option
                                    value="2"
                                    @selected($producto->estado == 2)
                                >
                                    Bajo stock
                                </option>

                                <option
                                    value="3"
                                    @selected($producto->estado == 3)
                                >
                                    Agotado
                                </option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="destacadoEditar{{ $producto->id }}"
                                    name="destacado"
                                    value="1"
                                    @checked($producto->destacado)
                                >

                                <label
                                    class="form-check-label"
                                    for="destacadoEditar{{ $producto->id }}"
                                >
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                        Producto recomendado
                                </label>

                            </div>

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Observación
                            </label>

                            <textarea
                                name="observacion"
                                class="form-control"
                                rows="3"
                                maxlength="255"
                            >{{ $producto->observacion }}</textarea>

                        </div>

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

{{-- Modal para crear producto --}}
<div
    class="modal fade"
    id="modalCrearProducto"
    tabindex="-1"
    aria-labelledby="tituloCrearProducto"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form
                action="{{ route('productos.store') }}"
                method="POST"
            >
                @csrf

                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="tituloCrearProducto"
                    >
                        <i class="bi bi-plus-circle me-1"></i>
                        Nuevo producto
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            value="{{ old('nombre') }}"
                            maxlength="150"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Categoría
                        </label>

                        <select
                            name="category_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Seleccione una categoría
                            </option>

                            @foreach($categorias as $categoria)

                                <option
                                    value="{{ $categoria->id }}"
                                    @selected(
                                        old('category_id') == $categoria->id
                                    )
                                >
                                    {{ $categoria->nombre }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Estado
                        </label>

                        <select
                            name="estado"
                            class="form-select"
                            required
                        >

                            <option
                                value="1"
                                @selected(old('estado', 1) == 1)
                            >
                                Disponible
                            </option>

                            <option
                                value="2"
                                @selected(old('estado') == 2)
                            >
                                Bajo stock
                            </option>

                            <option
                                value="3"
                                @selected(old('estado') == 3)
                            >
                                Agotado
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="destacadoCrear"
                                name="destacado"
                                value="1"
                                @checked(old('destacado'))
                            >

                            <label
                                class="form-check-label"
                                for="destacadoCrear"
                            >
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                Producto recomendado
                            </label>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Observación
                        </label>

                        <textarea
                            name="observacion"
                            class="form-control"
                            rows="3"
                            maxlength="255"
                            placeholder="Ingrese una observación opcional"
                        >{{ old('observacion') }}</textarea>

                    </div>

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
                        Registrar producto
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection