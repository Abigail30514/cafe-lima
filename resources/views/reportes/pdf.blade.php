<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <title>
        Reporte de disponibilidad
    </title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #212529;
        }

        .header {
            border-bottom: 3px solid #6f4e37;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0;
            color: #6f4e37;
            font-size: 23px;
        }

        .header p {
            margin: 5px 0 0;
            color: #6c757d;
        }

        .date {
            text-align: right;
            font-size: 10px;
            margin-top: -35px;
        }

        .summary {
            width: 100%;
            margin-bottom: 18px;
            border-spacing: 8px;
        }

        .summary td {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }

        .summary .number {
            display: block;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .summary .label {
            color: #6c757d;
            font-size: 10px;
        }

        .filters {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            margin-bottom: 15px;
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
            padding: 9px;
            text-align: left;
            font-size: 10px;
        }

        table.products td {
            border: 1px solid #dee2e6;
            padding: 8px;
            vertical-align: middle;
        }

        table.products tr:nth-child(even) {
            background: #f8f9fa;
        }

        .status {
            padding: 4px 7px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            font-size: 9px;
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

        .no-results {
            text-align: center;
            padding: 30px;
            color: #6c757d;
        }

        .footer {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            text-align: center;
            color: #6c757d;
            font-size: 9px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Café de Lima</h1>

        <p>
            Reporte de disponibilidad de productos
        </p>

        <div class="date">
            Generado:
            {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <table class="summary">
        <tr>
            <td>
                <span class="number">
                    {{ $totalProductos }}
                </span>

                <span class="label">
                    Total de productos
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

    <div class="filters">
        <strong>Filtros aplicados:</strong>

        Categoría:
        {{ !empty($datos['categoria'])
            ? 'Categoría seleccionada'
            : 'Todas' }}

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

        Desde:
        {{ !empty($datos['fecha_inicio'])
            ? \Carbon\Carbon::parse($datos['fecha_inicio'])->format('d/m/Y')
            : 'Sin fecha' }}

        &nbsp; | &nbsp;

        Hasta:
        {{ !empty($datos['fecha_fin'])
            ? \Carbon\Carbon::parse($datos['fecha_fin'])->format('d/m/Y')
            : 'Sin fecha' }}
    </div>

    <table class="products">
        <thead>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Estado actual</th>
                <th>Observación</th>
                <th>Última actualización</th>
            </tr>
        </thead>

        <tbody>
            @forelse($productos as $producto)
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
                        {{ $producto->category?->nombre ?? 'Sin categoría' }}
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
                        {{ $producto->observacion ?: 'Sin observación' }}
                    </td>

                    <td>
                        {{ $producto->updated_at?->format('d/m/Y H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-results">
                        No se encontraron productos con los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Sistema de Gestión de Disponibilidad — Restaurante Café de Lima
    </div>

</body>
</html>