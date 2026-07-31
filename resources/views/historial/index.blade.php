@extends('layouts.app')

@section('title', 'Historial')

@section('content')

<style>
    .history-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
    }

    .state-change {
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>

<div class="mb-4">
    <h2 class="fw-bold mb-1">Historial de cambios</h2>

    <p class="text-muted mb-0">
        Registro de las modificaciones realizadas en la disponibilidad.
    </p>
</div>

<div class="card history-card mb-4">
    <div class="card-body">

        <form
            method="GET"
            action="{{ route('historial.index') }}"
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
                        Nuevo estado
                    </label>

                    <select name="estado" class="form-select">
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

                <div class="col-md-3">
                    <label class="form-label">
                        Fecha
                    </label>

                    <input
                        type="date"
                        name="fecha"
                        class="form-control"
                        value="{{ request('fecha') }}"
                    >
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
                        href="{{ route('historial.index') }}"
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

<div class="card history-card">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Fecha y hora</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Cambio realizado</th>
                        <th>Usuario</th>
                        <th>Observación</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($historiales as $historial)

                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">
                                    {{ $historial->created_at->format('d/m/Y') }}
                                </div>

                                <small class="text-muted">
                                    {{ $historial->created_at->format('H:i') }}
                                </small>
                            </td>

                            <td class="fw-semibold">
                                {{ $historial->product->nombre ?? 'Producto eliminado' }}
                            </td>

                            <td>
                                {{ $historial->product?->category?->nombre ?? 'Sin categoría' }}
                            </td>

                            <td>
                                <div class="state-change">

                                    @if($historial->estado_anterior == 1)
                                        <span class="badge bg-success">
                                            Disponible
                                        </span>
                                    @elseif($historial->estado_anterior == 2)
                                        <span class="badge bg-warning text-dark">
                                            Bajo stock
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Agotado
                                        </span>
                                    @endif

                                    <i class="bi bi-arrow-right"></i>

                                    @if($historial->estado_nuevo == 1)
                                        <span class="badge bg-success">
                                            Disponible
                                        </span>
                                    @elseif($historial->estado_nuevo == 2)
                                        <span class="badge bg-warning text-dark">
                                            Bajo stock
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Agotado
                                        </span>
                                    @endif

                                </div>
                            </td>

                            <td>
                                <i class="bi bi-person-circle me-1"></i>

                                {{ $historial->user->name ?? 'Usuario no disponible' }}
                            </td>

                            <td>
                                {{ $historial->observacion ?: 'Sin observación' }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="6"
                                class="text-center py-5"
                            >
                                <i class="bi bi-clock-history fs-1 text-muted"></i>

                                <h6 class="fw-bold mt-3">
                                    No existen cambios registrados
                                </h6>

                                <p class="text-muted mb-0">
                                    Los cambios de estado aparecerán aquí.
                                </p>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        @if($historiales->hasPages())
            <div class="p-3">
                {{ $historiales->links() }}
            </div>
        @endif

    </div>
</div>

@endsection