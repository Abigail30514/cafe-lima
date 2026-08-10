@extends('layouts.app')

@section('title', 'Riesgo de Agotamiento')

@section('content')

<style>

    .risk-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
    }

    .score-box {
        min-width: 62px;
        display: inline-block;
        text-align: center;
        font-weight: 700;
    }

</style>


<div class="mb-4">

    <h2 class="fw-bold mb-1">
        Riesgo de agotamiento
    </h2>

    <p class="text-muted mb-0">
        Evaluación basada en el estado actual y comportamiento reciente del consumo.
    </p>

</div>


<div class="alert alert-light border mb-4">

    <div class="d-flex align-items-start gap-3">

        <i class="bi bi-info-circle text-primary fs-4"></i>

        <div>

            <strong>
                ¿Cómo se calcula?
            </strong>

            <div class="text-muted small mt-1">

                El nivel de riesgo considera el estado actual del plato,
                su promedio diario de consumo y la variación frente al
                periodo anterior.

            </div>

        </div>

    </div>

</div>


<div class="card risk-card">

    <div class="card-body p-0">

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
                            Promedio/día
                        </th>

                        <th class="text-center">
                            Tendencia
                        </th>

                        <th class="text-center">
                            Puntaje
                        </th>

                        <th class="text-center">
                            Riesgo
                        </th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($products as $item)

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


                            {{-- ESTADO --}}

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


                            {{-- CONSUMO --}}

                            <td class="text-center fw-semibold">

                                {{ $item['consumo_actual'] }}

                            </td>


                            {{-- PROMEDIO --}}

                            <td class="text-center">

                                {{ number_format(
                                    $item['promedio_diario'],
                                    2
                                ) }}

                            </td>


                            {{-- TENDENCIA --}}

                            <td class="text-center">

                                @if($item['tendencia'] === null)

                                    <span class="text-muted">
                                        Sin referencia
                                    </span>

                                @elseif($item['tendencia'] > 0)

                                    <span class="text-danger fw-semibold">

                                        <i class="bi bi-arrow-up-right"></i>

                                        +{{ $item['tendencia'] }}%

                                    </span>

                                @elseif($item['tendencia'] < 0)

                                    <span class="text-success fw-semibold">

                                        <i class="bi bi-arrow-down-right"></i>

                                        {{ $item['tendencia'] }}%

                                    </span>

                                @else

                                    <span class="text-muted">
                                        0%
                                    </span>

                                @endif

                            </td>


                            {{-- PUNTAJE --}}

                            <td class="text-center">

                                <span class="score-box">
                                    {{ $item['puntaje'] }}/100
                                </span>

                            </td>


                            {{-- NIVEL DE RIESGO --}}

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

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5 text-muted"
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

@endsection