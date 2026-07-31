@extends('layouts.app')

@section('title', 'Disponibilidad')

@section('content')

<style>
    .availability-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
    }

    .status-buttons .btn {
        min-width: 40px;
    }

    .status-current {
        min-width: 105px;
        display: inline-block;
        text-align: center;
    }
</style>

<div class="mb-4">
    <h2 class="fw-bold mb-1">
        Disponibilidad de productos
    </h2>

    <p class="text-muted mb-0">
        Actualiza rápidamente el estado de atención de cada producto.
    </p>
</div>

<div class="card availability-card mb-4">
    <div class="card-body">

        <form
            method="GET"
            action="{{ route('disponibilidad.index') }}"
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
                        <i class="bi bi-search"></i>
                        Buscar
                    </button>

                    <a
                        href="{{ route('disponibilidad.index') }}"
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

<div class="card availability-card">
    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Producto</th>
                        <th>Categoría</th>
                        <th>Estado actual</th>
                        <th>Observación</th>
                        <th class="text-center">
                            Cambiar disponibilidad
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($productos as $producto)

                        <tr>

                            <td class="ps-4 fw-semibold">
                                {{ $producto->nombre }}
                            </td>

                            <td>
                                {{ $producto->category->nombre ?? 'Sin categoría' }}
                            </td>

                            <td>
                                @if($producto->estado == 1)

                                    <span class="badge bg-success status-current">
                                        Disponible
                                    </span>

                                @elseif($producto->estado == 2)

                                    <span class="badge bg-warning text-dark status-current">
                                        Bajo stock
                                    </span>

                                @else

                                    <span class="badge bg-danger status-current">
                                        Agotado
                                    </span>

                                @endif
                            </td>

                            <td>
                                {{ $producto->observacion ?: 'Sin observación' }}
                            </td>

                            <td class="text-center">

                            @if(Auth::user()->esAdministrador() || Auth::user()->esCocina())

                                <form
                                    action="{{ route('productos.estado', $producto) }}"
                                    method="POST"
                                    class="d-inline-flex gap-2 status-buttons"
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

                                @else

                                        <span class="text-muted">
                                            <i class="bi bi-eye me-1"></i>
                                            Solo consulta
                                        </span>

                                @endif


                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="5"
                                class="text-center py-5"
                            >
                                <i class="bi bi-box-seam fs-1 text-muted"></i>

                                <h6 class="fw-bold mt-3">
                                    No se encontraron productos
                                </h6>

                                <p class="text-muted mb-0">
                                    Revisa los filtros seleccionados.
                                </p>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

        @if($productos->hasPages())
            <div class="p-3">
                {{ $productos->links() }}
            </div>
        @endif

    </div>
</div>

@endsection