@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

<style>
    .dashboard-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
    }

    .chart-container {
        position: relative;
        width: 220px;
        height: 220px;
        margin: auto;
    }

    .chart-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        pointer-events: none;
    }

    .chart-center strong {
        display: block;
        font-size: 32px;
        line-height: 1;
    }

    .chart-center span {
        color: #6c757d;
        font-size: 12px;
    }

    .indicator-dot {
        width: 10px;
        height: 10px;
        display: inline-block;
        border-radius: 50%;
        margin-right: 5px;
    }

    #calendario {
        font-size: 11px;
    }

    #calendario .fc-toolbar {
        margin-bottom: 10px;
    }

    #calendario .fc-toolbar-title {
        font-size: 16px;
        font-weight: 700;
        text-transform: capitalize;
    }

    #calendario .fc-button {
        padding: 3px 7px;
        font-size: 11px;
    }

    #calendario .fc-daygrid-day-frame {
        min-height: 31px;
    }

    #calendario .fc-col-header-cell-cushion,
    #calendario .fc-daygrid-day-number {
        color: #212529;
        text-decoration: none;
    }

    #calendario .fc-day-today {
        background: rgba(13, 110, 253, 0.12) !important;
    }

    .today-box {
        min-width: 110px;
        border-radius: 14px;
        background: #f4f7fb;
        text-align: center;
        padding: 12px;
    }

    .today-day {
        font-size: 12px;
        color: #6c757d;
        text-transform: capitalize;
    }

    .today-number {
        font-size: 32px;
        font-weight: 700;
        line-height: 1.1;
    }

    .today-month {
        font-size: 13px;
        color: #0d6efd;
        text-transform: capitalize;
    }

    .timeline-item {
        position: relative;
        padding-left: 28px;
        padding-bottom: 20px;
    }

    .timeline-item:not(:last-child)::before {
        content: "";
        position: absolute;
        left: 8px;
        top: 17px;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-dot {
        position: absolute;
        left: 2px;
        top: 5px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
    }

    .badge-prioridad{
    min-width:90px;
    text-align:center;
    }

    .badge-estado{
        min-width:90px;
        text-align:center;
    }
</style>

<div class="d-flex justify-content-between align-items-start mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Resumen operativo
        </h2>

        <p class="text-muted mb-0">
            Situación actual de la disponibilidad del restaurante.
        </p>
    </div>

    <div class="text-end">
        <small class="text-muted d-block">
            Última actualización
        </small>

        <strong>
            @if($ultimaActualizacion)
                {{ \Carbon\Carbon::parse($ultimaActualizacion)->format('d/m/Y H:i') }}
            @else
                Sin registros
            @endif
        </strong>
    </div>

</div>

<div class="row g-4 mb-4">

    {{-- Dona --}}
    <div class="col-lg-5">

        <div class="card dashboard-card h-100">
            <div class="card-body p-4">

                <h5 class="fw-bold mb-3">
                    <i class="bi bi-pie-chart-fill text-primary me-2"></i>
                    Disponibilidad general
                </h5>

                @if($totalProductos > 0)

                    @php
                        $porcentajeDisponibles = round(($disponibles / $totalProductos) * 100);
                        $porcentajeBajoStock = round(($bajoStock / $totalProductos) * 100);
                        $porcentajeAgotados = round(($agotados / $totalProductos) * 100);
                    @endphp

                    <div class="chart-container">

                        <canvas id="graficoDisponibilidad"></canvas>

                        <div class="chart-center">
                            <strong>{{ $totalProductos }}</strong>
                            <span>productos</span>
                        </div>

                    </div>

                    <div class="row text-center mt-4">

                        <div class="col-4">

                            <div class="fw-bold text-success fs-5">
                                {{ $porcentajeDisponibles }}%
                            </div>

                            <small class="text-muted">
                                <span
                                    class="indicator-dot"
                                    style="background: #198754;"
                                ></span>
                                Disponibles
                            </small>

                        </div>

                        <div class="col-4 border-start border-end">

                            <div class="fw-bold text-warning fs-5">
                                {{ $porcentajeBajoStock }}%
                            </div>

                            <small class="text-muted">
                                <span
                                    class="indicator-dot"
                                    style="background: #ffc107;"
                                ></span>
                                Bajo stock
                            </small>

                        </div>

                        <div class="col-4">

                            <div class="fw-bold text-danger fs-5">
                                {{ $porcentajeAgotados }}%
                            </div>

                            <small class="text-muted">
                                <span
                                    class="indicator-dot"
                                    style="background: #dc3545;"
                                ></span>
                                Agotados
                            </small>

                        </div>

                    </div>

                @else

                    <div class="text-center text-muted py-5">
                        No hay productos registrados.
                    </div>

                @endif

            </div>
        </div>

    </div>

    {{-- Calendario --}}
    <div class="col-lg-7">

        <div class="card dashboard-card h-100">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-calendar3 text-primary me-2"></i>
                            Calendario
                        </h5>

                        <small class="text-muted">
                            Selecciona una fecha del calendario.
                        </small>
                    </div>

                    <div class="today-box">

                        <div class="today-day">
                            {{ now()->translatedFormat('l') }}
                        </div>

                        <div class="today-number">
                            {{ now()->format('d') }}
                        </div>

                        <div class="today-month">
                            {{ now()->translatedFormat('F') }}
                        </div>

                    </div>

                </div>

                <div id="calendario"></div>

                <div
                    id="fechaSeleccionada"
                    class="alert alert-primary py-2 mt-2 mb-0 d-none"
                ></div>

            </div>
        </div>

    </div>

</div>

{{-- Alertas --}}
<div class="card dashboard-card mb-4">

    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                    Alertas importantes
                </h5>

                <small class="text-muted">
                    Productos que requieren atención inmediata.
                </small>
            </div>

            <div class="d-flex align-items-center gap-2">

                @if($totalAlertasCriticas > 0)

                    <span class="badge bg-dark rounded-pill">
                        <i class="bi bi-star-fill text-warning me-1"></i>
                        {{ $totalAlertasCriticas }} críticas
                    </span>

                @endif

                <span class="badge bg-danger rounded-pill">
                    {{ $productosCriticos->count() }} alertas
                </span>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Observación</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($productosCriticos as $producto)

                        <tr>

                            <td class="fw-semibold">

                                {{ $producto->nombre }}

                                @if($producto->destacado)

                                    <span
                                        class="badge bg-warning text-dark ms-1"
                                        title="Producto recomendado"
                                    >
                                        <i class="bi bi-star-fill"></i>
                                        Recomendado
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $producto->category->nombre ?? 'Sin categoría' }}
                            </td>

                            <td>
                                @if($producto->estado == 2)

                                    <span class="badge bg-warning text-dark badge-estado">
                                        Bajo stock
                                    </span>

                                @else

                                    <span class="badge bg-danger badge-estado">
                                        Agotado
                                    </span>

                                @endif
                            </td>

                            <td>

                                @if($producto->estado == 3 && $producto->destacado)

                                    <span class="badge bg-dark badge-prioridad">
                                        <i class="bi bi-exclamation-octagon-fill text-warning me-1"></i>
                                        Crítica
                                    </span>

                                @elseif($producto->estado == 3)

                                    <span class="badge bg-danger badge-prioridad">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i>
                                        Alta
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark badge-prioridad">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        Media
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $producto->observacion ?: 'Sin observación' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center py-4">

                                <i class="bi bi-check-circle-fill text-success fs-2"></i>

                                <p class="fw-semibold mt-2 mb-0">
                                    No existen alertas pendientes.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- Últimos cambios --}}
<div class="card dashboard-card">

    <div class="card-body p-4">

        <div class="mb-4">

            <h5 class="fw-bold mb-1">
                <i class="bi bi-clock-history text-primary me-2"></i>
                Últimos cambios realizados
            </h5>

            <small class="text-muted">
                Actividad reciente en la disponibilidad de productos.
            </small>

        </div>

    @forelse($ultimosCambios as $cambio)

        <div class="timeline-item">

            <span
                class="timeline-dot
                @if($cambio->estado_nuevo == 1)
                    bg-success
                @elseif($cambio->estado_nuevo == 2)
                    bg-warning
                @else
                    bg-danger
                @endif"
            ></span>

            <div class="d-flex justify-content-between align-items-start">

                <div>
                    <strong>
                        {{ $cambio->product->nombre ?? 'Producto eliminado' }}
                    </strong>

                    <div class="mt-1">

                        @if($cambio->estado_anterior == 1)
                            <span class="badge bg-success">Disponible</span>
                        @elseif($cambio->estado_anterior == 2)
                            <span class="badge bg-warning text-dark">Bajo stock</span>
                        @else
                            <span class="badge bg-danger">Agotado</span>
                        @endif

                        <i class="bi bi-arrow-right mx-2"></i>

                        @if($cambio->estado_nuevo == 1)
                            <span class="badge bg-success">Disponible</span>
                        @elseif($cambio->estado_nuevo == 2)
                            <span class="badge bg-warning text-dark">Bajo stock</span>
                        @else
                            <span class="badge bg-danger">Agotado</span>
                        @endif

                    </div>

                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ $cambio->user->name ?? 'Usuario no disponible' }}
                    </small>
                </div>

                <div class="text-end">
                    <small class="text-muted d-block">
                        {{ $cambio->created_at->diffForHumans() }}
                    </small>

                    <small class="text-muted">
                        {{ $cambio->created_at->format('d/m/Y H:i') }}
                    </small>
                </div>

            </div>

        </div>

    @empty

        <div class="text-center text-muted py-4">
            <i class="bi bi-clock-history fs-2"></i>

            <p class="mt-2 mb-0">
                No existen cambios registrados.
            </p>
        </div>

    @endforelse

    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.21/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const grafico = document.getElementById('graficoDisponibilidad');

    if (grafico && typeof Chart !== 'undefined') {

        new Chart(grafico, {
            type: 'doughnut',

            data: {
                labels: [
                    'Disponibles',
                    'Bajo stock',
                    'Agotados'
                ],

                datasets: [{
                    data: [
                        {{ $disponibles }},
                        {{ $bajoStock }},
                        {{ $agotados }}
                    ],

                    backgroundColor: [
                        '#198754',
                        '#ffc107',
                        '#dc3545'
                    ],

                    borderColor: '#ffffff',
                    borderWidth: 4,
                    hoverOffset: 7
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',

                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    const calendarioElemento = document.getElementById('calendario');
    const fechaSeleccionada = document.getElementById('fechaSeleccionada');

    if (
        calendarioElemento &&
        typeof FullCalendar !== 'undefined'
    ) {

        const calendario = new FullCalendar.Calendar(
            calendarioElemento,
            {
                locale: 'es',
                initialView: 'dayGridMonth',

                height: 285,

                fixedWeekCount: false,
                showNonCurrentDates: true,

                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'today'
                },

                buttonText: {
                    today: 'Hoy'
                },

                dateClick: function(info) {

                    const fecha = new Date(
                        info.dateStr + 'T00:00:00'
                    );

                    const texto = fecha.toLocaleDateString(
                        'es-PE',
                        {
                            weekday: 'long',
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        }
                    );

                    fechaSeleccionada.innerHTML =
                        '<i class="bi bi-calendar-check me-2"></i>' +
                        '<strong>' +
                        texto.charAt(0).toUpperCase() +
                        texto.slice(1) +
                        '</strong>';

                    fechaSeleccionada.classList.remove('d-none');
                }
            }
        );

        calendario.render();
    }
});
</script>

@endpush