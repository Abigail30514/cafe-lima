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
</style>

<div class="mb-4">
    <h2 class="fw-bold mb-1">
        Historial de disponibilidad y consumo
    </h2>

    <p class="text-muted mb-0">
        Consulta los cambios de disponibilidad y los consumos registrados de los platos.
    </p>
</div>


<div class="card history-card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('historial.index') }}"
        >

            <div class="row g-3 align-items-end">

                {{-- PRODUCTO --}}

                <div class="col-md-3">

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

                <div class="col-md-2">

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

                <div class="col-md-2">

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


                {{-- BOTONES --}}

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

                            {{-- FECHA --}}

                            <td class="ps-4">

                                <div class="fw-semibold">
                                    {{ $historial['fecha']->format('d/m/Y') }}
                                </div>

                                <small class="text-muted">
                                    {{ $historial['fecha']->format('H:i') }}
                                </small>

                            </td>


                            {{-- PRODUCTO --}}

                            <td class="fw-semibold">
                                {{ $historial['producto'] }}
                            </td>


                            {{-- CATEGORÍA --}}

                            <td>
                                {{ $historial['categoria'] }}
                            </td>


                            {{-- TIPO --}}

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


                            {{-- DETALLE --}}

                            <td>

                                @if($historial['tipo'] === 'consumo')

                                    <span class="fw-semibold">
                                        {{ $historial['cantidad'] }}
                                    </span>

                                    unidad(es) consumida(s)

                                @else

                                    <div class="state-change">

                                        {{-- ESTADO ANTERIOR --}}

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


                                        {{-- ESTADO NUEVO --}}

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


                            {{-- USUARIO --}}

                            <td>

                                <i class="bi bi-person-circle me-1"></i>

                                {{ $historial['usuario'] }}

                            </td>


                            {{-- OBSERVACIÓN --}}

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


        @if($historiales->hasPages())

            <div class="p-3">
                {{ $historiales->links() }}
            </div>

        @endif

    </div>

</div>

@endsection