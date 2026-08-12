@extends('layouts.app')

@section('title', 'Alertas de Reposición')

@section('content')

<style>

    .alert-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
    }

    .summary-card {
        border: 0;
        border-radius: 14px;
    }

    .recommendation-box {
        max-width: 420px;
        font-size: 13px;
        line-height: 1.45;
    }

    .alert-mobile-item {
        padding: 16px;
        border-bottom: 1px solid #eceff2;
    }

    .alert-mobile-item:last-child {
        border-bottom: 0;
    }

    .alert-mobile-title {
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.25;
    }

    .alert-mobile-meta {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .alert-mobile-recommendation {
        font-size: 0.9rem;
        line-height: 1.45;
        background: #f8f9fa;
        border: 1px solid #eceff2;
        border-radius: 10px;
        padding: 10px 12px;
    }

    @media (max-width: 767.98px) {

        .alerts-header {
            margin-bottom: 20px !important;
        }

        .alerts-header h2 {
            font-size: 1.55rem;
        }

        .alerts-header p {
            font-size: 0.95rem;
        }

        .summary-card .card-body {
            padding: 1rem;
        }

        .alerts-info {
            align-items: flex-start !important;
        }

        .alert-card {
            overflow: hidden;
        }
    }

</style>


<div class="mb-4 alerts-header">

    <h2 class="fw-bold mb-1">
        Alertas y recomendaciones de reposición
    </h2>

    <p class="text-muted mb-0">
        Alertas preventivas generadas según el comportamiento reciente
        del consumo y el riesgo de agotamiento.
    </p>

</div>


{{-- RESUMEN --}}

<div class="row g-3 g-md-4 mb-4">

    <div class="col-12 col-sm-4">

        <div class="card summary-card bg-danger-subtle h-100">

            <div class="card-body">

                <div class="d-flex align-items-center gap-3">

                    <i class="bi bi-exclamation-octagon-fill text-danger fs-2"></i>

                    <div>

                        <small class="text-muted">
                            Alertas críticas
                        </small>

                        <h2 class="fw-bold text-danger mb-0">
                            {{ $criticas }}
                        </h2>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col-12 col-sm-4">

        <div class="card summary-card bg-warning-subtle h-100">

            <div class="card-body">

                <div class="d-flex align-items-center gap-3">

                    <i class="bi bi-exclamation-triangle-fill text-warning fs-2"></i>

                    <div>

                        <small class="text-muted">
                            Prioridad alta
                        </small>

                        <h2 class="fw-bold mb-0">
                            {{ $altas }}
                        </h2>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col-12 col-sm-4">

        <div class="card summary-card bg-info-subtle h-100">

            <div class="card-body">

                <div class="d-flex align-items-center gap-3">

                    <i class="bi bi-info-circle-fill text-info fs-2"></i>

                    <div>

                        <small class="text-muted">
                            Prioridad media
                        </small>

                        <h2 class="fw-bold mb-0">
                            {{ $medias }}
                        </h2>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- INFORMACIÓN --}}

<div class="alert alert-light border mb-4 d-flex gap-2 alerts-info">

    <i class="bi bi-lightbulb-fill text-warning mt-1"></i>

    <div>
        Las recomendaciones se generan automáticamente a partir del
        nivel de riesgo calculado con el historial reciente de consumo.
    </div>

</div>


{{-- ALERTAS --}}

<div class="card alert-card">

    {{-- ============================================================ --}}
    {{-- TABLET / PC --}}
    {{-- ============================================================ --}}

    <div class="card-body p-0 d-none d-md-block">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">

                    <tr>
                        <th class="ps-4">
                            Plato
                        </th>

                        <th>
                            Categoría
                        </th>

                        <th class="text-center">
                            Estado
                        </th>

                        <th class="text-center">
                            Consumo 7 días
                        </th>

                        <th class="text-center">
                            Riesgo
                        </th>

                        <th>
                            Alerta
                        </th>

                        <th>
                            Recomendación
                        </th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($alertas as $item)

                        @php
                            $product = $item['product'];
                        @endphp

                        <tr>

                            <td class="ps-4 fw-semibold">
                                {{ $product->nombre }}
                            </td>


                            <td>
                                {{ $product->category?->nombre
                                    ?? 'Sin categoría' }}
                            </td>


                            <td class="text-center">

                                @if($product->estado == 1)

                                    <span class="badge bg-success">
                                        Disponible
                                    </span>

                                @elseif($product->estado == 2)

                                    <span class="badge bg-warning text-dark">
                                        Bajo stock
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Agotado
                                    </span>

                                @endif

                            </td>


                            <td class="text-center fw-semibold">
                                {{ $item['consumo_actual'] }}
                            </td>


                            <td class="text-center">

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


                                <div class="small text-muted mt-1">
                                    {{ $item['puntaje'] }}/100
                                </div>

                            </td>


                            <td>

                                @if($item['prioridad'] === 'Critica')

                                    <span class="text-danger fw-semibold">

                                        <i class="bi bi-exclamation-octagon-fill me-1"></i>

                                        {{ $item['alerta'] }}

                                    </span>

                                @elseif($item['prioridad'] === 'Alta')

                                    <span
                                        class="fw-semibold"
                                        style="color:#fd7e14;"
                                    >

                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>

                                        {{ $item['alerta'] }}

                                    </span>

                                @else

                                    <span class="text-warning-emphasis fw-semibold">

                                        <i class="bi bi-info-circle-fill me-1"></i>

                                        {{ $item['alerta'] }}

                                    </span>

                                @endif

                            </td>


                            <td>

                                <div class="recommendation-box">
                                    {{ $item['recomendacion'] }}
                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <i class="bi bi-check-circle-fill text-success fs-1"></i>

                                <h6 class="fw-bold mt-3">
                                    No existen alertas preventivas
                                </h6>

                                <p class="text-muted mb-0">
                                    Actualmente los productos analizados presentan un riesgo bajo.
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

        @forelse($alertas as $item)

            @php
                $product = $item['product'];
            @endphp

            <div class="alert-mobile-item">

                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">

                    <div>

                        <div class="alert-mobile-title">
                            {{ $product->nombre }}
                        </div>

                        <div class="alert-mobile-meta mt-1">
                            <i class="bi bi-tag me-1"></i>
                            {{ $product->category?->nombre ?? 'Sin categoría' }}
                        </div>

                    </div>

                    <div class="text-end">

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

                        <div class="small text-muted mt-1">
                            {{ $item['puntaje'] }}/100
                        </div>

                    </div>

                </div>


                <div class="d-flex flex-wrap gap-2 mb-3">

                    @if($product->estado == 1)

                        <span class="badge bg-success">
                            Disponible
                        </span>

                    @elseif($product->estado == 2)

                        <span class="badge bg-warning text-dark">
                            Bajo stock
                        </span>

                    @else

                        <span class="badge bg-danger">
                            Agotado
                        </span>

                    @endif

                    <span class="badge bg-secondary-subtle text-dark">
                        Consumo 7 días: {{ $item['consumo_actual'] }}
                    </span>

                </div>


                <div class="mb-3">

                    @if($item['prioridad'] === 'Critica')

                        <div class="text-danger fw-semibold">

                            <i class="bi bi-exclamation-octagon-fill me-1"></i>

                            {{ $item['alerta'] }}

                        </div>

                    @elseif($item['prioridad'] === 'Alta')

                        <div
                            class="fw-semibold"
                            style="color:#fd7e14;"
                        >

                            <i class="bi bi-exclamation-triangle-fill me-1"></i>

                            {{ $item['alerta'] }}

                        </div>

                    @else

                        <div class="text-warning-emphasis fw-semibold">

                            <i class="bi bi-info-circle-fill me-1"></i>

                            {{ $item['alerta'] }}

                        </div>

                    @endif

                </div>


                <div class="alert-mobile-recommendation">

                    <div class="fw-semibold mb-1">
                        <i class="bi bi-lightbulb me-1"></i>
                        Recomendación
                    </div>

                    {{ $item['recomendacion'] }}

                </div>

            </div>

        @empty

            <div class="text-center py-5 px-3">

                <i class="bi bi-check-circle-fill text-success fs-1"></i>

                <h6 class="fw-bold mt-3">
                    No existen alertas preventivas
                </h6>

                <p class="text-muted mb-0">
                    Actualmente los productos analizados presentan un riesgo bajo.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection