@extends('layouts.app')

@section('title', 'Reportes')

@section('content')

<style>

    .report-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
    }

    .summary-card {
        border: 0;
        border-radius: 14px;
        min-height: 105px;
    }

    .summary-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 21px;
        flex-shrink: 0;
    }

</style>


@php

    $filtrosExport = array_filter(
        [
            'categoria' =>
                request('categoria'),

            'estado' =>
                request('estado'),

            'fecha_inicio' =>
                $fechaInicio,

            'fecha_fin' =>
                $fechaFin,
        ],
        fn ($valor) =>
            $valor !== null
            && $valor !== ''
    );

@endphp


{{-- CABECERA --}}

<div class="d-flex justify-content-between align-items-start mb-4">

    <div>

        <h2 class="fw-bold mb-1">
            Reportes operativos
        </h2>

        <p class="text-muted mb-0">
            Consulta disponibilidad, consumo y riesgo de agotamiento de los platos.
        </p>

    </div>


    <div class="d-flex gap-2">

        <a
            href="{{
                route(
                    'reportes.excel',
                    $filtrosExport
                )
            }}"
            class="btn btn-success"
        >

            <i class="bi bi-file-earmark-excel me-1"></i>

            Excel

        </a>


        <a
            href="{{
                route(
                    'reportes.pdf',
                    $filtrosExport
                )
            }}"
            class="btn btn-danger"
        >

            <i class="bi bi-file-earmark-pdf me-1"></i>

            PDF

        </a>

    </div>

</div>


{{-- FILTROS --}}

<div class="card report-card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('reportes.index') }}"
        >

            <div class="row g-3 align-items-end">


                {{-- CATEGORÍA --}}

                <div class="col-md-3">

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
                                    request('categoria')
                                    == $categoria->id
                                )
                            >

                                {{ $categoria->nombre }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ESTADO --}}

                <div class="col-md-3">

                    <label class="form-label">
                        Estado actual
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
                            @selected(
                                request('estado') == '1'
                            )
                        >
                            Disponible
                        </option>

                        <option
                            value="2"
                            @selected(
                                request('estado') == '2'
                            )
                        >
                            Bajo stock
                        </option>

                        <option
                            value="3"
                            @selected(
                                request('estado') == '3'
                            )
                        >
                            Agotado
                        </option>

                    </select>

                </div>


                {{-- DESDE --}}

                <div class="col-md-2">

                    <label class="form-label">
                        Desde
                    </label>

                    <input
                        type="date"
                        name="fecha_inicio"
                        class="form-control"
                        value="{{ $fechaInicio }}"
                        required
                    >

                </div>


                {{-- HASTA --}}

                <div class="col-md-2">

                    <label class="form-label">
                        Hasta
                    </label>

                    <input
                        type="date"
                        name="fecha_fin"
                        class="form-control"
                        value="{{ $fechaFin }}"
                        required
                    >

                </div>


                {{-- BOTONES --}}

                <div class="col-md-2 d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-funnel"></i>

                        Filtrar

                    </button>


                    <a
                        href="{{ route('reportes.index') }}"
                        class="btn btn-secondary"
                        title="Restablecer filtros"
                    >

                        <i class="bi bi-arrow-clockwise"></i>

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- PERIODO --}}

<div class="alert alert-light border mb-4">

    <i class="bi bi-calendar-range text-primary me-2"></i>

    <strong>Periodo analizado:</strong>

    {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}

    al

    {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}

</div>


{{-- INDICADORES --}}

<div class="row g-3 mb-4">


    {{-- PRODUCTOS --}}

    <div class="col-md-6 col-xl-2">

        <div class="card summary-card shadow-sm h-100">

            <div class="card-body d-flex align-items-center">

                <div
                    class="summary-icon bg-primary bg-opacity-10 text-primary me-3"
                >
                    <i class="bi bi-cup-hot"></i>
                </div>

                <div>

                    <small class="text-muted">
                        Platos
                    </small>

                    <h3 class="fw-bold mb-0">
                        {{ $totalProductos }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- CONSUMO TOTAL --}}

    <div class="col-md-6 col-xl-2">

        <div class="card summary-card shadow-sm h-100">

            <div class="card-body d-flex align-items-center">

                <div
                    class="summary-icon bg-info bg-opacity-10 text-info me-3"
                >

                    <i class="bi bi-cart-check"></i>

                </div>

                <div>

                    <small class="text-muted">
                        Consumo
                    </small>

                    <h3 class="fw-bold mb-0">
                        {{ $totalConsumo }}
                    </h3>

                    <small class="text-muted">
                        unidades
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- DISPONIBLES --}}

    <div class="col-md-6 col-xl-2">

        <div class="card summary-card shadow-sm h-100">

            <div class="card-body d-flex align-items-center">

                <div
                    class="summary-icon bg-success bg-opacity-10 text-success me-3"
                >

                    <i class="bi bi-check-circle"></i>

                </div>

                <div>

                    <small class="text-muted">
                        Disponibles
                    </small>

                    <h3 class="fw-bold mb-0">
                        {{ $disponibles }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- BAJO STOCK --}}

    <div class="col-md-6 col-xl-2">

        <div class="card summary-card shadow-sm h-100">

            <div class="card-body d-flex align-items-center">

                <div
                    class="summary-icon bg-warning bg-opacity-10 text-warning me-3"
                >

                    <i class="bi bi-exclamation-triangle"></i>

                </div>

                <div>

                    <small class="text-muted">
                        Bajo stock
                    </small>

                    <h3 class="fw-bold mb-0">
                        {{ $bajoStock }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- AGOTADOS --}}

    <div class="col-md-6 col-xl-2">

        <div class="card summary-card shadow-sm h-100">

            <div class="card-body d-flex align-items-center">

                <div
                    class="summary-icon bg-danger bg-opacity-10 text-danger me-3"
                >

                    <i class="bi bi-x-circle"></i>

                </div>

                <div>

                    <small class="text-muted">
                        Agotados
                    </small>

                    <h3 class="fw-bold mb-0">
                        {{ $agotados }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- RIESGO --}}

    <div class="col-md-6 col-xl-2">

        <div class="card summary-card shadow-sm h-100">

            <div class="card-body d-flex align-items-center">

                <div
                    class="summary-icon bg-danger bg-opacity-10 text-danger me-3"
                >

                    <i class="bi bi-speedometer2"></i>

                </div>

                <div>

                    <small class="text-muted">
                        Riesgo alto/crítico
                    </small>

                    <h3 class="fw-bold mb-0">
                        {{ $riesgosAltosCriticos }}
                    </h3>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- TABLA --}}

<div class="card report-card">

    <div class="card-header bg-white border-0 py-3">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="fw-bold mb-1">
                    Reporte operativo
                </h5>

                <small class="text-muted">

                    Disponibilidad, consumo y riesgo según los filtros seleccionados.

                </small>

            </div>


            <span class="badge bg-primary rounded-pill">

                {{ $totalProductos }} registros

            </span>

        </div>

    </div>


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

                        <th>
                            Estado
                        </th>

                        <th class="text-center">
                            Consumo periodo
                        </th>

                        <th class="text-center">
                            Promedio/día
                        </th>

                        <th class="text-center">
                            Riesgo actual
                        </th>

                        <th class="text-center">
                            Puntaje
                        </th>

                        <th>
                            Observación
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($reportes as $item)

                        @php
                            $producto = $item['producto'];
                        @endphp

                        <tr>


                            <td class="ps-4 fw-semibold">

                                {{ $producto->nombre }}

                            </td>


                            <td>

                                {{ $producto->category?->nombre
                                    ?? 'Sin categoría' }}

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


                            <td class="text-center fw-semibold">

                                {{ $item['consumo_periodo'] }}

                            </td>


                            <td class="text-center">

                                {{ number_format(
                                    $item['promedio_periodo'],
                                    2
                                ) }}

                            </td>


                            <td class="text-center">

                                @if($item['riesgo'] === 'Critico')

                                    <span class="badge bg-danger">
                                        Crítico
                                    </span>

                                @elseif($item['riesgo'] === 'Alto')

                                    <span
                                        class="badge"
                                        style="background:#fd7e14;"
                                    >
                                        Alto
                                    </span>

                                @elseif($item['riesgo'] === 'Medio')

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

                                {{ $item['puntaje'] }}/100

                            </td>


                            <td>

                                {{ $producto->observacion
                                    ?: 'Sin observación' }}

                            </td>


                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5"
                            >

                                <i
                                    class="bi bi-file-earmark-bar-graph fs-1 text-muted"
                                ></i>

                                <h6 class="fw-bold mt-3">

                                    No se encontraron resultados

                                </h6>

                                <p class="text-muted mb-0">

                                    Modifica los filtros seleccionados.

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection