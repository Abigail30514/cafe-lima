<?php

namespace App\Http\Controllers;

use App\Models\ProductConsumption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConsumptionAnalysisController extends Controller
{
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | PERIODO DE ANÁLISIS
        |--------------------------------------------------------------------------
        */

        $dias = (int) $request->input('dias', 7);

        if (!in_array($dias, [7, 15, 30])) {
            $dias = 7;
        }

        $desde = now()
            ->subDays($dias - 1)
            ->startOfDay();

        $hasta = now()->endOfDay();


        /*
        |--------------------------------------------------------------------------
        | INDICADORES GENERALES
        |--------------------------------------------------------------------------
        */

        $totalConsumido = ProductConsumption::whereBetween(
                'consumed_at',
                [$desde, $hasta]
            )
            ->sum('quantity');

        $totalRegistros = ProductConsumption::whereBetween(
                'consumed_at',
                [$desde, $hasta]
            )
            ->count();

        $productosConConsumo = ProductConsumption::whereBetween(
                'consumed_at',
                [$desde, $hasta]
            )
            ->distinct('product_id')
            ->count('product_id');

        $promedioDiario = $dias > 0
            ? round($totalConsumido / $dias, 2)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | COMPARACIÓN CON EL PERIODO ANTERIOR
        |--------------------------------------------------------------------------
        */

        $hastaAnterior = $desde
            ->copy()
            ->subSecond();

        $desdeAnterior = $desde
            ->copy()
            ->subDays($dias);

        $totalPeriodoAnterior = ProductConsumption::whereBetween(
                'consumed_at',
                [$desdeAnterior, $hastaAnterior]
            )
            ->sum('quantity');

        if ($totalPeriodoAnterior > 0) {

            $variacion = round(
                (($totalConsumido - $totalPeriodoAnterior)
                    / $totalPeriodoAnterior) * 100,
                1
            );

        } else {

            $variacion = null;
        }


        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS CON MAYOR CONSUMO
        |--------------------------------------------------------------------------
        */

        $productosMasConsumidos = ProductConsumption::query()
            ->select('product_id')
            ->selectRaw('SUM(quantity) AS total_consumido')
            ->selectRaw('COUNT(*) AS total_registros')
            ->with('product.category')
            ->whereBetween(
                'consumed_at',
                [$desde, $hasta]
            )
            ->groupBy('product_id')
            ->orderByDesc('total_consumido')
            ->take(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CONSUMO POR DÍA
        |--------------------------------------------------------------------------
        */

        $consumoBD = ProductConsumption::query()
            ->selectRaw('DATE(consumed_at) AS fecha')
            ->selectRaw('SUM(quantity) AS total')
            ->whereBetween(
                'consumed_at',
                [$desde, $hasta]
            )
            ->groupByRaw('DATE(consumed_at)')
            ->orderBy('fecha')
            ->pluck('total', 'fecha');


        /*
        |--------------------------------------------------------------------------
        | COMPLETAR DÍAS SIN CONSUMO
        |--------------------------------------------------------------------------
        */

        $labelsConsumo = [];
        $datosConsumo = [];

        $fecha = $desde->copy();

        while ($fecha->lte($hasta)) {

            $clave = $fecha->format('Y-m-d');

            $labelsConsumo[] = $fecha->format('d/m');

            $datosConsumo[] = isset($consumoBD[$clave])
                ? (int) $consumoBD[$clave]
                : 0;

            $fecha->addDay();
        }


        return view(
            'analisis-consumo.index',
            compact(
                'dias',
                'desde',
                'hasta',
                'totalConsumido',
                'totalRegistros',
                'productosConConsumo',
                'promedioDiario',
                'totalPeriodoAnterior',
                'variacion',
                'productosMasConsumidos',
                'labelsConsumo',
                'datosConsumo'
            )
        );
    }
}