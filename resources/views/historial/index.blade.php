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
        flex-wrap: wrap;
    }

    .history-mobile-item {
        padding: 16px;
        border-bottom: 1px solid #eceff2;
    }

    .history-mobile-item:last-child {
        border-bottom: 0;
    }

    .history-mobile-title {
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.25;
    }

    .history-mobile-meta {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .history-detail-box {
        background: #f8f9fa;
        border: 1px solid #eceff2;
        border-radius: 10px;
        padding: 10px 12px;
    }

    @media (max-width: 767.98px) {

        .history-header {
            margin-bottom: 20px !important;
        }

        .history-header h2 {
            font-size: 1.55rem;
        }

        .history-header p {
            font-size: 0.95rem;
        }

        .history-filter-card .card-body {
            padding: 1rem;
        }

        .history-filter-actions {
            display: grid !important;
            grid-template-columns: 1fr auto;
            gap: 8px !important;
        }

        .history-filter-actions .btn-primary {
            width: 100%;
        }

        .history-card {
            overflow: hidden;
        }
    }
</style>

<div class="mb-4 history-header">
    <h2 class="fw-bold mb-1">
        Historial de disponibilidad y consumo
    </h2>

    <p class="text-muted mb-0">
        Consulta los cambios de disponibilidad y los consumos registrados de los platos.
    </p>
</div>


<div class="card history-card history-filter-card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('historial.index') }}"
        >

            <div class="row g-3 align-items-end">

                {{-- PRODUCTO --}}

                <div class="col-12 col-md-3">

                    <label class="form-label">
                        Buscar plato
                    </label>

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Ejemplo: Cappuccino"
                        value="{{ request('buscar') }}"
                    >

                </div>


                {{-- TIPO DE MOVIMIENTO --}}

                <div class="col-12 col-sm-6 col-md-2">

                    <label class="form-label">
                        Tipo
                    </label>

                    <select
                        name="tipo"
                        class="form-select"
                    >

                        <option value="">
                            Todos
                        </option>

                        <option
                            value="disponibilidad"
                            @selected(request('tipo') === 'disponibilidad')
                        >
                            Disponibilidad
                        </option>

                        <option
                            value="consumo"
                            @selected(request('tipo') === 'consumo')
                        >
                            Consumo
                        </option>

                    </select>

                </div>


                {{-- ESTADO --}}

                <div class="col-12 col-sm-6 col-md-2">

                    <label class="form-label">
                        Estado
                    </label>

                    <select
                        name="estado"
                        class="form-select"
                    >

                        <option value="">
                            Todos
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


                {{-- FECHA --}}

                <div class="col-12 col-sm-6 col-md-3">

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


                {{-- BOTONES --}}

                <div class="col-12 col-sm-6 col-md-2 d-flex gap-2 history-filter-actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-search me-1"></i>
                        Buscar
                    </button>

                    <a
                        href="{{ route('historial.index') }}"
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


<div class="card history-card">

    {{-- ============================================================ --}}
    {{-- TABLET / PC --}}
    {{-- ============================================================ --}}

    <div class="card-body p-0 d-none d-md-block">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">

                    <tr>
                        <th class="ps-4">Fecha y hora</th>
                        <th>Plato</th>
                        <th>Categoría</th>
                        <th>Tipo</th>
                        <th>Detalle</th>
                        <th>Usuario</th>
                        <th>Observación</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($historiales as $historial)

                        <tr>

                            <td class="ps-4">

                                <div class="fw-semibold">
                                    {{ $historial['fecha']->format('d/m/Y') }}
                                </div>

                                <small class="text-muted">
                                    {{ $historial['fecha']->format('H:i') }}
                                </small>

                            </td>


                            <td class="fw-semibold">
                                {{ $historial['producto'] }}
                            </td>


                            <td>
                                {{ $historial['categoria'] }}
                            </td>


                            <td>

                                @if($historial['tipo'] === 'consumo')

                                    <span class="badge bg-primary">
                                        <i class="bi bi-cart-check me-1"></i>
                                        Consumo
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        <i class="bi bi-arrow-repeat me-1"></i>
                                        Disponibilidad
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($historial['tipo'] === 'consumo')

                                    <span class="fw-semibold">
                                        {{ $historial['cantidad'] }}
                                    </span>

                                    unidad(es) consumida(s)

                                @else

                                    <div class="state-change">

                                        @if($historial['estado_anterior'] == 1)

                                            <span class="badge bg-success">
                                                Disponible
                                            </span>

                                        @elseif($historial['estado_anterior'] == 2)

                                            <span class="badge bg-warning text-dark">
                                                Bajo stock
                                            </span>

                                        @elseif($historial['estado_anterior'] == 3)

                                            <span class="badge bg-danger">
                                                Agotado
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Sin estado
                                            </span>

                                        @endif


                                        <i class="bi bi-arrow-right"></i>


                                        @if($historial['estado_nuevo'] == 1)

                                            <span class="badge bg-success">
                                                Disponible
                                            </span>

                                        @elseif($historial['estado_nuevo'] == 2)

                                            <span class="badge bg-warning text-dark">
                                                Bajo stock
                                            </span>

                                        @elseif($historial['estado_nuevo'] == 3)

                                            <span class="badge bg-danger">
                                                Agotado
                                            </span>

                                        @endif

                                    </div>

                                @endif

                            </td>


                            <td>

                                <i class="bi bi-person-circle me-1"></i>

                                {{ $historial['usuario'] }}

                            </td>


                            <td>

                                {{ $historial['observacion']
                                    ?: 'Sin observación' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <i
                                    class="bi bi-clock-history fs-1 text-muted"
                                ></i>

                                <h6 class="fw-bold mt-3">
                                    No existen movimientos registrados
                                </h6>

                                <p class="text-muted mb-0">
                                    Los cambios de disponibilidad y consumos aparecerán aquí.
                                </p>

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

        @forelse($historiales as $historial)

            <div class="history-mobile-item">

                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">

                    <div>

                        <div class="history-mobile-title">
                            {{ $historial['producto'] }}
                        </div>

                        <div class="history-mobile-meta mt-1">
                            <i class="bi bi-tag me-1"></i>
                            {{ $historial['categoria'] }}
                        </div>

                    </div>

                    <div class="text-end">

                        @if($historial['tipo'] === 'consumo')

                            <span class="badge bg-primary">
                                <i class="bi bi-cart-check me-1"></i>
                                Consumo
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                <i class="bi bi-arrow-repeat me-1"></i>
                                Disponibilidad
                            </span>

                        @endif

                    </div>

                </div>


                <div class="history-mobile-meta mb-2">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ $historial['fecha']->format('d/m/Y H:i') }}
                </div>


                <div class="history-detail-box mb-2">

                    @if($historial['tipo'] === 'consumo')

                        <div class="fw-semibold">
                            {{ $historial['cantidad'] }}
                            unidad(es) consumida(s)
                        </div>

                    @else

                        <div class="state-change">

                            @if($historial['estado_anterior'] == 1)

                                <span class="badge bg-success">
                                    Disponible
                                </span>

                            @elseif($historial['estado_anterior'] == 2)

                                <span class="badge bg-warning text-dark">
                                    Bajo stock
                                </span>

                            @elseif($historial['estado_anterior'] == 3)

                                <span class="badge bg-danger">
                                    Agotado
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Sin estado
                                </span>

                            @endif


                            <i class="bi bi-arrow-right"></i>


                            @if($historial['estado_nuevo'] == 1)

                                <span class="badge bg-success">
                                    Disponible
                                </span>

                            @elseif($historial['estado_nuevo'] == 2)

                                <span class="badge bg-warning text-dark">
                                    Bajo stock
                                </span>

                            @elseif($historial['estado_nuevo'] == 3)

                                <span class="badge bg-danger">
                                    Agotado
                                </span>

                            @endif

                        </div>

                    @endif

                </div>


                <div class="history-mobile-meta mb-1">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ $historial['usuario'] }}
                </div>

                <div class="history-mobile-meta">
                    <i class="bi bi-chat-left-text me-1"></i>
                    {{ $historial['observacion'] ?: 'Sin observación' }}
                </div>

            </div>

        @empty

            <div class="text-center py-5 px-3">

                <i
                    class="bi bi-clock-history fs-1 text-muted"
                ></i>

                <h6 class="fw-bold mt-3">
                    No existen movimientos registrados
                </h6>

                <p class="text-muted mb-0">
                    Los cambios de disponibilidad y consumos aparecerán aquí.
                </p>

            </div>

        @endforelse

    </div>


    @if($historiales->hasPages())

        <div class="p-3 border-top">
            {{ $historiales->links() }}
        </div>

    @endif

</div>

@endsection