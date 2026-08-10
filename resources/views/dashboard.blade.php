@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

<style>

    .dashboard-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
    }

    .metric-card {
        border: 0;
        border-radius: 15px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .metric-icon {
        width: 50px;
        height: 50px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
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
        font-size: 30px;
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
        margin-right: 6px;
    }

    .consumption-chart {
        height: 290px;
        position: relative;
    }

    .risk-score {
        min-width: 65px;
        text-align: center;
        display: inline-block;
    }

    .progress-product {
        height: 7px;
        border-radius: 10px;
    }

    .activity-item {
        border-bottom: 1px solid #eeeeee;
        padding: 12px 0;
    }

    .activity-item:last-child {
        border-bottom: 0;
    }

    .activity-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

</style>


{{-- CABECERA --}}

<div class="d-flex justify-content-between align-items-start mb-4">

    <div>

        <h2 class="fw-bold mb-1">
            Resumen operativo
        </h2>

        <p class="text-muted mb-0">
            Estado actual del consumo, disponibilidad y riesgo de agotamiento.
        </p>

    </div>


    <div class="text-end">

        <small class="text-muted d-block">
            Última actualización
        </small>

        <strong>

            @if($ultimaActualizacion)

                <i class="bi bi-clock me-1"></i>

                {{ $ultimaActualizacion->format('d/m/Y H:i') }}

            @else

                Sin registros

            @endif

        </strong>

    </div>

</div>


{{-- ================================================================ --}}
{{-- INDICADORES PRINCIPALES --}}
{{-- ================================================================ --}}

<div class="row g-3 mb-4">


    {{-- CONSUMO HOY --}}

    <div class="col-xl-3 col-md-6">

        <div class="card metric-card h-100">

            <div class="card-body">

                <div class="d-flex align-items-center gap-3">

                    <div
                        class="metric-icon bg-success-subtle text-success"
                    >
                        <i class="bi bi-cart-check-fill"></i>
                    </div>

                    <div>

                        <small class="text-muted">
                            Consumo de hoy
                        </small>

                        <h3 class="fw-bold mb-0">
                            {{ $consumoHoy }}
                        </h3>

                        <small class="text-muted">
                            unidades
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- CONSUMO 7 DÍAS --}}

    <div class="col-xl-3 col-md-6">

        <div class="card metric-card h-100">

            <div class="card-body">

                <div class="d-flex align-items-center gap-3">

                    <div
                        class="metric-icon bg-primary-subtle text-primary"
                    >
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>

                    <div>

                        <small class="text-muted">
                            Consumo últimos 7 días
                        </small>

                        <h3 class="fw-bold mb-0">
                            {{ $consumo7Dias }}
                        </h3>

                        <small class="text-primary">
                            Prom. {{ $promedioConsumo7Dias }}/día
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- RIESGO ALTO --}}

    <div class="col-xl-3 col-md-6">

        <div class="card metric-card h-100">

            <div class="card-body">

                <div class="d-flex align-items-center gap-3">

                    <div
                        class="metric-icon bg-warning-subtle text-warning"
                    >
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>

                    <div>

                        <small class="text-muted">
                            Riesgo alto
                        </small>

                        <h3 class="fw-bold mb-0">
                            {{ $riesgosAltos }}
                        </h3>

                        <small class="text-muted">
                            platos
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- RIESGO CRÍTICO --}}

    <div class="col-xl-3 col-md-6">

        <div class="card metric-card h-100">

            <div class="card-body">

                <div class="d-flex align-items-center gap-3">

                    <div
                        class="metric-icon bg-danger-subtle text-danger"
                    >
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>

                    <div>

                        <small class="text-muted">
                            Riesgo crítico
                        </small>

                        <h3 class="fw-bold mb-0">
                            {{ $riesgosCriticos }}
                        </h3>

                        <small class="text-danger">
                            requieren atención
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================================================================ --}}
{{-- DISPONIBILIDAD + TOP RIESGOS --}}
{{-- ================================================================ --}}

<div class="row g-4 mb-4">


    {{-- DISPONIBILIDAD --}}

    <div class="col-lg-5">

        <div class="card dashboard-card h-100">

            <div class="card-body p-4">

                <h5 class="fw-bold mb-3">

                    <i class="bi bi-pie-chart-fill text-primary me-2"></i>

                    Disponibilidad general

                </h5>


                @if($totalProductos > 0)

                    @php

                        $porcentajeDisponibles =
                            round(
                                ($disponibles / $totalProductos) * 100
                            );

                        $porcentajeBajoStock =
                            round(
                                ($bajoStock / $totalProductos) * 100
                            );

                        $porcentajeAgotados =
                            round(
                                ($agotados / $totalProductos) * 100
                            );

                    @endphp


                    <div class="chart-container">

                        <canvas id="graficoDisponibilidad"></canvas>

                        <div class="chart-center">

                            <strong>
                                {{ $totalProductos }}
                            </strong>

                            <span>
                                platos
                            </span>

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
                                    style="background:#198754;"
                                ></span>

                                Disponible

                            </small>

                        </div>


                        <div class="col-4 border-start border-end">

                            <div class="fw-bold text-warning fs-5">

                                {{ $porcentajeBajoStock }}%

                            </div>

                            <small class="text-muted">

                                <span
                                    class="indicator-dot"
                                    style="background:#ffc107;"
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
                                    style="background:#dc3545;"
                                ></span>

                                Agotado

                            </small>

                        </div>

                    </div>


                @else

                    <div class="text-center text-muted py-5">

                        No existen platos registrados.

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- TOP RIESGOS --}}

    <div class="col-lg-7">

        <div class="card dashboard-card h-100">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h5 class="fw-bold mb-1">

                            <i class="bi bi-speedometer2 text-danger me-2"></i>

                            Platos con mayor riesgo

                        </h5>

                        <small class="text-muted">

                            Productos priorizados por el algoritmo.

                        </small>

                    </div>


                    @if(Auth::user()->esAdministrador() || Auth::user()->esCocina())

                        <a
                            href="{{ route('riesgo-agotamiento.index') }}"
                            class="btn btn-outline-primary btn-sm"
                        >
                            Ver análisis
                        </a>

                    @endif

                </div>


                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>Plato</th>
                                <th>Estado</th>
                                <th>Riesgo</th>
                                <th class="text-center">Puntaje</th>
                            </tr>

                        </thead>


                        <tbody>

                            @forelse($topRiesgos as $item)

                                @php
                                    $producto = $item['product'];
                                @endphp

                                <tr>

                                    <td class="fw-semibold">

                                        {{ $producto->nombre }}

                                        <div class="small text-muted">

                                            {{ $producto->category?->nombre
                                                ?? 'Sin categoría' }}

                                        </div>

                                    </td>


                                    <td>

                                        @if($producto->estado == 1)

                                            <span class="badge bg-success">
                                                Disponible
                                            </span>

                                        @elseif($producto->estado == 2)

                                            <span class="badge bg-warning text-dark">
                                                Bajo stock
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Agotado
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($item['nivel'] === 'Critico')

                                            <span class="badge bg-danger">
                                                Crítico
                                            </span>

                                        @elseif($item['nivel'] === 'Alto')

                                            <span
                                                class="badge"
                                                style="background:#fd7e14;"
                                            >
                                                Alto
                                            </span>

                                        @elseif($item['nivel'] === 'Medio')

                                            <span class="badge bg-warning text-dark">
                                                Medio
                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                Bajo
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-center fw-bold">

                                        <span class="risk-score">

                                            {{ $item['puntaje'] }}/100

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="4"
                                        class="text-center py-4 text-muted"
                                    >

                                        No existen productos para analizar.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================================================================ --}}
{{-- COMPORTAMIENTO DEL CONSUMO --}}
{{-- ================================================================ --}}

<div class="card dashboard-card mb-4">

    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h5 class="fw-bold mb-1">

                    <i class="bi bi-graph-up text-primary me-2"></i>

                    Comportamiento del consumo

                </h5>

                <small class="text-muted">

                    Evolución de las unidades consumidas durante los últimos 7 días.

                </small>

            </div>


            @if(Auth::user()->esAdministrador() || Auth::user()->esCocina())

                <a
                    href="{{ route('analisis-consumo.index') }}"
                    class="btn btn-outline-primary btn-sm"
                >
                    Ver análisis completo
                </a>

            @endif

        </div>


        <div class="consumption-chart">

            <canvas id="graficoConsumo"></canvas>

        </div>

    </div>

</div>


{{-- ================================================================ --}}
{{-- ALERTAS Y RECOMENDACIONES --}}
{{-- ================================================================ --}}

<div class="card dashboard-card mb-4">

    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h5 class="fw-bold mb-1">

                    <i class="bi bi-bell-fill text-warning me-2"></i>

                    Alertas y recomendaciones

                </h5>

                <small class="text-muted">

                    Productos que requieren seguimiento o reposición.

                </small>

            </div>


            @if(Auth::user()->esAdministrador() || Auth::user()->esCocina())

                <a
                    href="{{ route('alertas-reposicion.index') }}"
                    class="btn btn-outline-primary btn-sm"
                >
                    Ver todas
                </a>

            @endif

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th>Plato</th>
                        <th>Estado</th>
                        <th>Riesgo</th>
                        <th class="text-center">
                            Consumo 7 días
                        </th>
                        <th>Recomendación</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($alertasRecomendaciones as $item)

                        @php
                            $producto = $item['product'];
                        @endphp

                        <tr>

                            <td class="fw-semibold">

                                {{ $producto->nombre }}

                            </td>


                            <td>

                                @if($producto->estado == 1)

                                    <span class="badge bg-success">
                                        Disponible
                                    </span>

                                @elseif($producto->estado == 2)

                                    <span class="badge bg-warning text-dark">
                                        Bajo stock
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Agotado
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($item['nivel'] === 'Critico')

                                    <span class="badge bg-danger">
                                        Crítico
                                    </span>

                                @elseif($item['nivel'] === 'Alto')

                                    <span
                                        class="badge"
                                        style="background:#fd7e14;"
                                    >
                                        Alto
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Medio
                                    </span>

                                @endif

                            </td>


                            <td class="text-center fw-semibold">

                                {{ $item['consumo_actual'] }}

                            </td>


                            <td style="max-width:420px;">

                                {{ $item['recomendacion'] }}

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-4"
                            >

                                <i
                                    class="bi bi-check-circle-fill text-success fs-2"
                                ></i>

                                <p class="fw-semibold mt-2 mb-0">

                                    No existen alertas preventivas.

                                </p>

                                <small class="text-muted">

                                    Los platos presentan actualmente riesgo bajo.

                                </small>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ================================================================ --}}
{{-- MÁS CONSUMIDOS + ACTIVIDAD RECIENTE --}}
{{-- ================================================================ --}}

<div class="row g-4">


    {{-- MÁS CONSUMIDOS --}}

    <div class="col-lg-5">

        <div class="card dashboard-card h-100">

            <div class="card-body p-4">

                <h5 class="fw-bold mb-1">

                    <i class="bi bi-trophy-fill text-warning me-2"></i>

                    Platos más consumidos

                </h5>

                <small class="text-muted">

                    Ranking de los últimos 7 días.

                </small>


                @php

                    $maxConsumo =
                        $platosMasConsumidos->max(
                            'total_consumido'
                        ) ?: 1;

                @endphp


                <div class="mt-4">

                    @forelse($platosMasConsumidos as $index => $item)

                        <div class="mb-3">

                            <div
                                class="d-flex justify-content-between align-items-center mb-1"
                            >

                                <div>

                                    <span class="text-muted me-2">

                                        {{ $index + 1 }}.

                                    </span>

                                    <strong>

                                        {{ $item->product?->nombre
                                            ?? 'Producto eliminado' }}

                                    </strong>

                                </div>


                                <span class="fw-bold">

                                    {{ $item->total_consumido }}

                                </span>

                            </div>


                            <div class="progress progress-product">

                                <div
                                    class="progress-bar"
                                    style="
                                        width:
                                        {{
                                            round(
                                                ($item->total_consumido
                                                / $maxConsumo) * 100
                                            )
                                        }}%;
                                    "
                                ></div>

                            </div>

                        </div>


                    @empty

                        <div class="text-center text-muted py-5">

                            No existen consumos registrados.

                        </div>

                    @endforelse

                </div>


                @if(Auth::user()->esAdministrador() || Auth::user()->esCocina())

                    <div class="text-end mt-3">

                        <a
                            href="{{ route('analisis-consumo.index') }}"
                            class="text-decoration-none"
                        >

                            Ver todos

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- ACTIVIDAD RECIENTE --}}

    <div class="col-lg-7">

        <div class="card dashboard-card h-100">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">

                            <i class="bi bi-clock-history text-primary me-2"></i>

                            Actividad reciente

                        </h5>

                        <small class="text-muted">

                            Últimos movimientos de disponibilidad y consumo.

                        </small>

                    </div>


                    @if(Auth::user()->esAdministrador() || Auth::user()->esCocina())

                        <a
                            href="{{ route('historial.index') }}"
                            class="btn btn-outline-secondary btn-sm"
                        >
                            Historial
                        </a>

                    @endif

                </div>


                <div class="mt-3">

                    @forelse($actividadReciente as $actividad)

                        <div class="activity-item">

                            <div class="d-flex align-items-start gap-3">


                                @if($actividad['tipo'] === 'consumo')

                                    <div
                                        class="activity-icon bg-success-subtle text-success"
                                    >
                                        <i class="bi bi-cart-check-fill"></i>
                                    </div>

                                @else

                                    <div
                                        class="activity-icon bg-warning-subtle text-warning"
                                    >
                                        <i class="bi bi-arrow-repeat"></i>
                                    </div>

                                @endif


                                <div class="flex-grow-1">

                                    <div
                                        class="d-flex justify-content-between gap-3"
                                    >

                                        <div>

                                            <strong>

                                                {{ $actividad['producto'] }}

                                            </strong>

                                            <div class="small">

                                                {{ $actividad['detalle'] }}

                                            </div>


                                            <small class="text-muted">

                                                <i class="bi bi-person-circle me-1"></i>

                                                {{ $actividad['usuario'] }}

                                            </small>

                                        </div>


                                        <div class="text-end">

                                            @if($actividad['tipo'] === 'consumo')

                                                <span
                                                    class="badge bg-success-subtle text-success"
                                                >
                                                    Consumo
                                                </span>

                                            @else

                                                <span
                                                    class="badge bg-warning-subtle text-warning-emphasis"
                                                >
                                                    Disponibilidad
                                                </span>

                                            @endif


                                            <small class="text-muted d-block mt-1">

                                                {{ $actividad['fecha']->diffForHumans() }}

                                            </small>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                    @empty

                        <div class="text-center text-muted py-5">

                            No existen actividades recientes.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | GRÁFICO DE DISPONIBILIDAD
    |--------------------------------------------------------------------------
    */

    const graficoDisponibilidad =
        document.getElementById('graficoDisponibilidad');


    if (
        graficoDisponibilidad &&
        typeof Chart !== 'undefined'
    ) {

        new Chart(
            graficoDisponibilidad,
            {

                type: 'doughnut',

                data: {

                    labels: [
                        'Disponible',
                        'Bajo stock',
                        'Agotado'
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

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | GRÁFICO DE CONSUMO
    |--------------------------------------------------------------------------
    */

    const graficoConsumo =
        document.getElementById('graficoConsumo');


    if (
        graficoConsumo &&
        typeof Chart !== 'undefined'
    ) {

        new Chart(
            graficoConsumo,
            {

                type: 'line',

                data: {

                    labels:
                        @json($labelsConsumo),

                    datasets: [{

                        label: 'Unidades consumidas',

                        data:
                            @json($datosConsumo),

                        borderColor:
                            '#0d6efd',

                        backgroundColor:
                            'rgba(13, 110, 253, 0.08)',

                        borderWidth: 3,

                        tension: 0.35,

                        fill: true,

                        pointRadius: 5,

                        pointHoverRadius: 7

                    }]
                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },

                    scales: {

                        y: {

                            beginAtZero: true,

                            ticks: {
                                precision: 0
                            }

                        }

                    },

                    plugins: {

                        legend: {
                            display: false
                        }

                    }

                }

            }
        );

    }

});

</script>

@endpush