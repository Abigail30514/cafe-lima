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
        Evaluación del riesgo según el estado actual y el comportamiento reciente del consumo.
    </p>

</div>


<div
    class="d-flex align-items-center gap-2 px-3 py-2 mb-4 rounded-3"
    style="
        background: #f8f9fa;
        border: 1px solid #e5e7eb;
    "
>
    <i class="bi bi-info-circle-fill text-primary"></i>

    <small class="text-muted">

        <strong class="text-dark">
            Cálculo del riesgo:
        </strong>

        considera el estado actual del plato, el promedio diario
        y la tendencia reciente del consumo.

    </small>
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