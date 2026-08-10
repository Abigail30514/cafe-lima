<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Reporte operativo - Café de Lima
    </title>


    <style>

        @page {
            margin: 22px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #212529;
        }

        .header {
            border-bottom: 3px solid #6f4e37;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .header h1 {
            margin: 0;
            color: #6f4e37;
            font-size: 21px;
        }

        .header p {
            margin: 4px 0 0;
            color: #6c757d;
        }

        .date {
            text-align: right;
            font-size: 9px;
            margin-top: -32px;
        }

        .summary {
            width: 100%;
            margin-bottom: 12px;
            border-spacing: 5px;
        }

        .summary td {
            border: 1px solid #dee2e6;
            padding: 7px;
            text-align: center;
        }

        .summary .number {
            display: block;
            font-size: 17px;
            font-weight: bold;
        }

        .summary .label {
            color: #6c757d;
            font-size: 8px;
        }

        .filters {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            margin-bottom: 12px;
        }

        .filters strong {
            color: #6f4e37;
        }

        table.products {
            width: 100%;
            border-collapse: collapse;
        }

        table.products th {
            background: #212529;
            color: white;
            padding: 6px;
            text-align: left;
            font-size: 8px;
        }

        table.products td {
            border: 1px solid #dee2e6;
            padding: 6px;
            vertical-align: middle;
        }

        table.products tr:nth-child(even) {
            background: #f8f9fa;
        }

        .status {
            padding: 3px 5px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
            font-size: 8px;
        }

        .available {
            background: #198754;
        }

        .low-stock {
            background: #d39e00;
        }

        .out-of-stock {
            background: #dc3545;
        }

        .risk-low {
            color: #198754;
            font-weight: bold;
        }

        .risk-medium {
            color: #b58100;
            font-weight: bold;
        }

        .risk-high {
            color: #fd7e14;
            font-weight: bold;
        }

        .risk-critical {
            color: #dc3545;
            font-weight: bold;
        }

        .no-results {
            text-align: center;
            padding: 25px;
            color: #6c757d;
        }

        .footer {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;

            text-align: center;

            color: #6c757d;
            font-size: 8px;
        }

    </style>

</head>


<body>


<div class="header">

    <h1>
        Café de Lima
    </h1>

    <p>
        Reporte operativo de disponibilidad, consumo y riesgo de agotamiento
    </p>


    <div class="date">

        Generado:

        {{ now()->format('d/m/Y H:i') }}

    </div>

</div>


{{-- RESUMEN --}}

<table class="summary">

    <tr>

        <td>

            <span class="number">
                {{ $totalProductos }}
            </span>

            <span class="label">
                Platos analizados
            </span>

        </td>


        <td>

            <span class="number">
                {{ $totalConsumo }}
            </span>

            <span class="label">
                Consumo del periodo
            </span>

        </td>


        <td>

            <span class="number">
                {{ $riesgosAltosCriticos }}
            </span>

            <span class="label">
                Riesgo alto/crítico
            </span>

        </td>


        <td>

            <span class="number">
                {{ $disponibles }}
            </span>

            <span class="label">
                Disponibles
            </span>

        </td>


        <td>

            <span class="number">
                {{ $bajoStock }}
            </span>

            <span class="label">
                Bajo stock
            </span>

        </td>


        <td>

            <span class="number">
                {{ $agotados }}
            </span>

            <span class="label">
                Agotados
            </span>

        </td>

    </tr>

</table>


{{-- FILTROS --}}

<div class="filters">

    <strong>Filtros aplicados:</strong>


    Categoría:

    {{ $categoriaSeleccionada
        ?: 'Todas' }}


    &nbsp; | &nbsp;


    Estado:

    @if(!empty($datos['estado']))

        @if($datos['estado'] == 1)

            Disponible

        @elseif($datos['estado'] == 2)

            Bajo stock

        @else

            Agotado

        @endif

    @else

        Todos

    @endif


    &nbsp; | &nbsp;


    Periodo:

    {{ \Carbon\Carbon::parse($fechaInicio)
        ->format('d/m/Y') }}

    -

    {{ \Carbon\Carbon::parse($fechaFin)
        ->format('d/m/Y') }}

</div>


{{-- TABLA --}}

<table class="products">

    <thead>

        <tr>

            <th>
                ID
            </th>

            <th>
                Plato
            </th>

            <th>
                Categoría
            </th>

            <th>
                Estado
            </th>

            <th>
                Consumo
            </th>

            <th>
                Prom./día
            </th>

            <th>
                Riesgo
            </th>

            <th>
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

                <td>
                    {{ $producto->id }}
                </td>


                <td>

                    <strong>
                        {{ $producto->nombre }}
                    </strong>

                </td>


                <td>

                    {{ $producto->category?->nombre
                        ?? 'Sin categoría' }}

                </td>


                <td>

                    @if($producto->estado == 1)

                        <span class="status available">
                            Disponible
                        </span>

                    @elseif($producto->estado == 2)

                        <span class="status low-stock">
                            Bajo stock
                        </span>

                    @else

                        <span class="status out-of-stock">
                            Agotado
                        </span>

                    @endif

                </td>


                <td>

                    {{ $item['consumo_periodo'] }}

                </td>


                <td>

                    {{ number_format(
                        $item['promedio_periodo'],
                        2
                    ) }}

                </td>


                <td>

                    @if($item['riesgo'] === 'Critico')

                        <span class="risk-critical">
                            Crítico
                        </span>

                    @elseif($item['riesgo'] === 'Alto')

                        <span class="risk-high">
                            Alto
                        </span>

                    @elseif($item['riesgo'] === 'Medio')

                        <span class="risk-medium">
                            Medio
                        </span>

                    @else

                        <span class="risk-low">
                            Bajo
                        </span>

                    @endif

                </td>


                <td>

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
                    colspan="9"
                    class="no-results"
                >

                    No se encontraron datos con los filtros seleccionados.

                </td>

            </tr>

        @endforelse

    </tbody>

</table>


<div class="footer">

    Sistema de Gestión de Disponibilidad y Consumo
    — Restaurante Café de Lima

</div>


</body>

</html>