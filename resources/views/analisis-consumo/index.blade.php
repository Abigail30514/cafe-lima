@extends('layouts.app')

@section('title', 'Análisis de Consumo')

@section('content')

<style>
    .analysis-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
    }

    .metric-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    .chart-box {
        position: relative;
        height: 320px;
    }

    .analysis-mobile-item {
        padding: 16px;
        border-bottom: 1px solid #eceff2;
    }

    .analysis-mobile-item:last-child {
        border-bottom: 0;
    }

    .analysis-mobile-title {
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.25;
    }

    .analysis-mobile-meta {
        font-size: 0.875rem;
        color: #6c757d;
    }

    @media (max-width: 767.98px) {

        .analysis-header {
            flex-direction: column;
            align-items: stretch !important;
            gap: 16px;
            margin-bottom: 20px !important;
        }

        .analysis-header h2 {
            font-size: 1.55rem;
        }

        .analysis-header p {
            font-size: 0.95rem;
        }

        .analysis-period-form {
            width: 100%;
            align-items: stretch !important;
        }

        .analysis-period-form label {
            margin-bottom: 4px;
        }

        .analysis-period-form .form-select {
            width: 100%;
        }

        .analysis-card .card-body {
            padding: 1rem !important;
        }

        .chart-box {
            height: 240px;
        }

        .metric-icon {
            width: 44px;
            height: 44px;
            font-size: 20px;
        }
    }

    @media (max-width: 575.98px) {

        .chart-box {
            height: 220px;
        }

        .analysis-mobile-item .badge {
            font-size: 0.8rem;
        }
    }
</style>


<div class="d-flex justify-content-between align-items-start mb-4 analysis-header">

    <div>
        <h2 class="fw-bold mb-1">
            Análisis de consumo
        </h2>

        <p class="text-muted mb-0">
            Comportamiento del consumo registrado de los platos.
        </p>
    </div>


    <form
        method="GET"
        action="{{ route('analisis-consumo.index') }}"
        class="d-flex align-items-center gap-2 analysis-period-form"
    >

        <label class="text-muted">
            Periodo:
        </label>

        <select
            name="dias"
            class="form-select"
            onchange="this.form.submit()"
        >

            <option
                value="7"
                @selected($dias == 7)
            >
                Últimos 7 días
            </option>

            <option
                value="15"
                @selected($dias == 15)
            >
                Últimos 15 días
            </option>

            <option
                value="30"
                @selected($dias == 30)
            >
                Últimos 30 días
            </option>

        </select>

    </form>

</div>


{{-- INDICADORES --}}

<div class="row g-3 g-md-4 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">

        <div class="card analysis-card h-100">

            <div class="card-body">

                <div class="d-flex align-items-center gap-3">

                    <div class="metric-icon bg-primary-subtle text-primary">
                        <i class="bi bi-cart-check"></i>
                    </div>

                    <div>
                        <small class="text-muted">
                            Consumo total
                        </small>

                        <h3 class="fw-bold mb-0">
                            {{ $totalConsumido }}
                        </h3>

                        <small class="text-muted">
                            unidades
                        </small>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col-12 col-sm-6 col-xl-3">

        <div class="card analysis-card h-100">

            <div class="card-body">

                <div class="d-flex align-items-center gap-3">

                    <div class="metric-icon bg-success-subtle text-success">
                        <i class="bi bi-calendar-check"></i>
                    </div>

                    <div>
                        <small class="text-muted">
                            Promedio diario
                        </small>

                        <h3 class="fw-bold mb-0">
                            {{ number_format($promedioDiario, 1) }}
                        </h3>

                        <small class="text-muted">
                            unidades/día
                        </small>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col-12 col-sm-6 col-xl-3">

        <div class="card analysis-card h-100">

            <div class="card-body">

                <div class="d-flex align-items-center gap-3">

                    <div class="metric-icon bg-warning-subtle text-warning">
                        <i class="bi bi-cup-hot"></i>
                    </div>

                    <div>
                        <small class="text-muted">
                            Platos con consumo
                        </small>

                        <h3 class="fw-bold mb-0">
                            {{ $productosConConsumo }}
                        </h3>

                        <small class="text-muted">
                            productos
                        </small>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col-12 col-sm-6 col-xl-3">

        <div class="card analysis-card h-100">

            <div class="card-body">

                <div class="d-flex align-items-center gap-3">

                    <div
                        class="metric-icon
                        @if($variacion === null)
                            bg-secondary-subtle text-secondary
                        @elseif($variacion > 0)
                            bg-danger-subtle text-danger
                        @elseif($variacion < 0)
                            bg-success-subtle text-success
                        @else
                            bg-secondary-subtle text-secondary
                        @endif"
                    >

                        @if($variacion !== null && $variacion > 0)

                            <i class="bi bi-arrow-up-right"></i>

                        @elseif($variacion !== null && $variacion < 0)

                            <i class="bi bi-arrow-down-right"></i>

                        @else

                            <i class="bi bi-dash-lg"></i>

                        @endif

                    </div>

                    <div>

                        <small class="text-muted">
                            Variación
                        </small>

                        <h3 class="fw-bold mb-0">

                            @if($variacion === null)

                                N/D

                            @else

                                {{ $variacion > 0 ? '+' : '' }}
                                {{ $variacion }}%

                            @endif

                        </h3>

                        <small class="text-muted">
                            vs. periodo anterior
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- GRÁFICO --}}

<div class="card analysis-card mb-4">

    <div class="card-body p-4">

        <div class="mb-3">

            <h5 class="fw-bold mb-1">

                <i class="bi bi-graph-up text-primary me-2"></i>

                Evolución del consumo

            </h5>

            <small class="text-muted">

                Del {{ $desde->format('d/m/Y') }}
                al {{ $hasta->format('d/m/Y') }}.

            </small>

        </div>


        @if($totalRegistros > 0)

            <div class="chart-box">

                <canvas id="graficoConsumo"></canvas>

            </div>

        @else

            <div class="text-center py-5 text-muted">

                <i class="bi bi-bar-chart fs-1"></i>

                <p class="mt-2 mb-0">
                    No existen consumos registrados para este periodo.
                </p>

            </div>

        @endif

    </div>

</div>


{{-- PRODUCTOS MÁS CONSUMIDOS --}}

<div class="card analysis-card">

    <div class="card-body p-4">

        <div class="mb-3">

            <h5 class="fw-bold mb-1">

                <i class="bi bi-trophy text-warning me-2"></i>

                Platos con mayor consumo

            </h5>

            <small class="text-muted">
                Ranking según las unidades registradas en el periodo seleccionado.
            </small>

        </div>


        {{-- TABLET / PC --}}

        <div class="table-responsive d-none d-md-block">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th>#</th>
                        <th>Plato</th>
                        <th>Categoría</th>
                        <th class="text-center">
                            Registros
                        </th>
                        <th class="text-center">
                            Total consumido
                        </th>
                        <th class="text-center">
                            Promedio diario
                        </th>
                    </tr>

                </thead>


                <tbody>

                    @forelse(
                        $productosMasConsumidos as $index => $item
                    )

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td class="fw-semibold">

                                {{ $item->product?->nombre
                                    ?? 'Producto eliminado' }}

                            </td>

                            <td>

                                {{ $item->product?->category?->nombre
                                    ?? 'Sin categoría' }}

                            </td>

                            <td class="text-center">

                                {{ $item->total_registros }}

                            </td>

                            <td class="text-center">

                                <span class="badge bg-primary rounded-pill">

                                    {{ $item->total_consumido }}

                                </span>

                            </td>

                            <td class="text-center">

                                {{ number_format(
                                    $item->total_consumido / $dias,
                                    1
                                ) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5 text-muted"
                            >

                                No existen datos de consumo para analizar.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- MÓVIL --}}

        <div class="d-md-none">

            @forelse(
                $productosMasConsumidos as $index => $item
            )

                <div class="analysis-mobile-item">

                    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">

                        <div>

                            <div class="analysis-mobile-title">
                                {{ $index + 1 }}.
                                {{ $item->product?->nombre
                                    ?? 'Producto eliminado' }}
                            </div>

                            <div class="analysis-mobile-meta mt-1">
                                <i class="bi bi-tag me-1"></i>
                                {{ $item->product?->category?->nombre
                                    ?? 'Sin categoría' }}
                            </div>

                        </div>

                        <span class="badge bg-primary rounded-pill">
                            {{ $item->total_consumido }}
                        </span>

                    </div>

                    <div class="row g-2 mt-1">

                        <div class="col-6">
                            <div class="analysis-mobile-meta">
                                Registros
                            </div>
                            <div class="fw-semibold">
                                {{ $item->total_registros }}
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="analysis-mobile-meta">
                                Promedio diario
                            </div>
                            <div class="fw-semibold">
                                {{ number_format(
                                    $item->total_consumido / $dias,
                                    1
                                ) }}
                            </div>
                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center py-5 text-muted">
                    No existen datos de consumo para analizar.
                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('graficoConsumo');

    if (!canvas || typeof Chart === 'undefined') {
        return;
    }

    new Chart(canvas, {

        type: 'line',

        data: {

            labels: @json($labelsConsumo),

            datasets: [{
                label: 'Unidades consumidas',

                data: @json($datosConsumo),

                borderColor: '#6f4e37',

                backgroundColor: 'rgba(111, 78, 55, 0.12)',

                borderWidth: 3,

                tension: 0.35,

                fill: true,

                pointRadius: 4,

                pointHoverRadius: 6
            }]
        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            interaction: {
                mode: 'index',
                intersect: false
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
                },

                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            }
        }
    });

});
</script>

@endpush