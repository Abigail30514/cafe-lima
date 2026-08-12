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

    .availability-mobile-item {
        border-bottom: 1px solid #eceff2;
        padding: 16px;
    }

    .availability-mobile-item:last-child {
        border-bottom: 0;
    }

    .availability-mobile-title {
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.25;
    }

    .availability-mobile-category,
    .availability-mobile-observation {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .availability-mobile-actions .btn {
        min-height: 44px;
        min-width: 44px;
        flex: 1 1 0;
    }

    @media (max-width: 767.98px) {

        .availability-header {
            margin-bottom: 20px !important;
        }

        .availability-header h2 {
            font-size: 1.55rem;
        }

        .availability-header p {
            font-size: 0.95rem;
        }

        .availability-filter-card .card-body {
            padding: 1rem;
        }

        .availability-filter-actions {
            display: grid !important;
            grid-template-columns: 1fr auto;
            gap: 8px !important;
        }

        .availability-filter-actions .btn {
            min-height: 44px;
        }

        .availability-filter-actions .btn-primary {
            width: 100%;
        }

        .availability-mobile-card {
            overflow: hidden;
        }

        .status-current {
            min-width: 95px;
        }
    }

    @media (max-width: 575.98px) {

        .availability-mobile-actions {
            width: 100%;
        }

        .availability-mobile-actions .btn {
            padding-left: 12px;
            padding-right: 12px;
        }
    }
</style>

<div class="mb-4 availability-header">
    <h2 class="fw-bold mb-1">
        Disponibilidad de productos
    </h2>

    <p class="text-muted mb-0">
        Actualiza rápidamente el estado de atención de cada producto.
    </p>
</div>

<div class="card availability-card availability-filter-card mb-4">
    <div class="card-body">

        <form
            method="GET"
            action="{{ route('disponibilidad.index') }}"
        >
            <div class="row g-3 align-items-end">

                <div class="col-12 col-md-4">
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

                <div class="col-12 col-sm-6 col-md-3">
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

                <div class="col-12 col-sm-6 col-md-3">
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

                <div class="col-12 col-md-2 d-flex gap-2 availability-filter-actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-search me-1"></i>
                        Buscar
                    </button>

                    <a
                        href="{{ route('disponibilidad.index') }}"
                        class="btn btn-secondary"
                        title="Limpiar filtros"
                        aria-label="Limpiar filtros"
                    >
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>

                </div>

            </div>
        </form>

    </div>
</div>

{{-- ================================================================ --}}
{{-- VISTA TABLET / PC --}}
{{-- ================================================================ --}}

<div class="card availability-card d-none d-md-block">
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

{{-- ================================================================ --}}
{{-- VISTA MÓVIL --}}
{{-- ================================================================ --}}

<div class="card availability-card availability-mobile-card d-md-none">

    @forelse($productos as $producto)

        <div class="availability-mobile-item">

            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">

                <div class="min-w-0">

                    <div class="availability-mobile-title">
                        {{ $producto->nombre }}
                    </div>

                    <div class="availability-mobile-category mt-1">
                        <i class="bi bi-tag me-1"></i>
                        {{ $producto->category->nombre ?? 'Sin categoría' }}
                    </div>

                </div>

                <div class="flex-shrink-0">

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

                </div>

            </div>

            <div class="availability-mobile-observation mb-3">
                <i class="bi bi-chat-left-text me-1"></i>
                {{ $producto->observacion ?: 'Sin observación' }}
            </div>

            @if(Auth::user()->esAdministrador() || Auth::user()->esCocina())

                <form
                    action="{{ route('productos.estado', $producto) }}"
                    method="POST"
                    class="d-flex gap-2 availability-mobile-actions"
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
                        <i class="bi bi-check-circle me-1"></i>
                        <span class="d-none d-sm-inline">Disponible</span>
                    </button>

                    <button
                        type="submit"
                        name="estado"
                        value="2"
                        class="btn btn-warning btn-sm"
                        title="Marcar como bajo stock"
                        @disabled($producto->estado == 2)
                    >
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <span class="d-none d-sm-inline">Bajo stock</span>
                    </button>

                    <button
                        type="submit"
                        name="estado"
                        value="3"
                        class="btn btn-danger btn-sm"
                        title="Marcar como agotado"
                        @disabled($producto->estado == 3)
                    >
                        <i class="bi bi-x-circle me-1"></i>
                        <span class="d-none d-sm-inline">Agotado</span>
                    </button>

                </form>

            @else

                <div class="text-muted small">
                    <i class="bi bi-eye me-1"></i>
                    Solo consulta
                </div>

            @endif

        </div>

    @empty

        <div class="text-center py-5 px-3">
            <i class="bi bi-box-seam fs-1 text-muted"></i>

            <h6 class="fw-bold mt-3">
                No se encontraron productos
            </h6>

            <p class="text-muted mb-0">
                Revisa los filtros seleccionados.
            </p>
        </div>

    @endforelse

    @if($productos->hasPages())
        <div class="p-3 border-top">
            {{ $productos->links() }}
        </div>
    @endif

</div>

@endsection