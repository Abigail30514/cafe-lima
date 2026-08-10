<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductConsumption;
use App\Models\ProductStatusHistory;
use App\Services\StockoutRiskService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(
        StockoutRiskService $riskService
    ): View {

        /*
        |--------------------------------------------------------------------------
        | PERIODO PRINCIPAL: ÚLTIMOS 7 DÍAS
        |--------------------------------------------------------------------------
        */

        $inicio7Dias = now()
            ->subDays(6)
            ->startOfDay();

        $finActual = now()->endOfDay();


        /*
        |--------------------------------------------------------------------------
        | DISPONIBILIDAD
        |--------------------------------------------------------------------------
        */

        $disponibles = Product::where('estado', 1)->count();
        $bajoStock = Product::where('estado', 2)->count();
        $agotados = Product::where('estado', 3)->count();

        $totalProductos =
            $disponibles +
            $bajoStock +
            $agotados;


        /*
        |--------------------------------------------------------------------------
        | INDICADORES DE CONSUMO
        |--------------------------------------------------------------------------
        */

        $consumoHoy = ProductConsumption::whereDate(
                'consumed_at',
                now()->toDateString()
            )
            ->sum('quantity');

        $consumo7Dias = ProductConsumption::whereBetween(
                'consumed_at',
                [$inicio7Dias, $finActual]
            )
            ->sum('quantity');

        $promedioConsumo7Dias = round(
            $consumo7Dias / 7,
            1
        );


        /*
        |--------------------------------------------------------------------------
        | RIESGO DE AGOTAMIENTO
        |--------------------------------------------------------------------------
        */

        $riesgos = Product::with('category')
            ->get()
            ->map(function ($product) use ($riskService) {

                $risk = $riskService->calculate($product);

                $recommendation = $riskService->recommendation(
                    $product,
                    $risk
                );

                return [
                    'product' => $product,
                    'consumo_actual' => $risk['consumo_actual'],
                    'consumo_anterior' => $risk['consumo_anterior'],
                    'promedio_diario' => $risk['promedio_diario'],
                    'tendencia' => $risk['tendencia'],
                    'puntaje' => $risk['puntaje'],
                    'nivel' => $risk['nivel'],
                    'alerta' => $recommendation['alerta'],
                    'recomendacion' => $recommendation['recomendacion'],
                    'prioridad' => $recommendation['prioridad'],
                ];
            })
            ->sortByDesc('puntaje')
            ->values();


        $riesgosCriticos = $riesgos
            ->where('nivel', 'Critico')
            ->count();

        $riesgosAltos = $riesgos
            ->where('nivel', 'Alto')
            ->count();

        /*
        | Los 3 productos con mayor puntaje.
        */

        $topRiesgos = $riesgos
            ->take(3)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | ALERTAS Y RECOMENDACIONES
        |--------------------------------------------------------------------------
        */

        $alertasRecomendaciones = $riesgos
            ->filter(function ($item) {
                return $item['puntaje'] >= 30;
            })
            ->take(5)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | CONSUMO DE LOS ÚLTIMOS 7 DÍAS
        |--------------------------------------------------------------------------
        */

        $consumoPorDiaBD = ProductConsumption::query()
            ->selectRaw('DATE(consumed_at) AS fecha')
            ->selectRaw('SUM(quantity) AS total')
            ->whereBetween(
                'consumed_at',
                [$inicio7Dias, $finActual]
            )
            ->groupByRaw('DATE(consumed_at)')
            ->orderBy('fecha')
            ->pluck('total', 'fecha');


        $labelsConsumo = [];
        $datosConsumo = [];

        $fecha = $inicio7Dias->copy();

        while ($fecha->lte($finActual)) {

            $clave = $fecha->format('Y-m-d');

            $labelsConsumo[] = ucfirst(
                $fecha->translatedFormat('D')
            );

            $datosConsumo[] = isset($consumoPorDiaBD[$clave])
                ? (int) $consumoPorDiaBD[$clave]
                : 0;

            $fecha->addDay();
        }


        /*
        |--------------------------------------------------------------------------
        | PLATOS MÁS CONSUMIDOS
        |--------------------------------------------------------------------------
        */

        $platosMasConsumidos = ProductConsumption::query()
            ->select('product_id')
            ->selectRaw(
                'SUM(quantity) AS total_consumido'
            )
            ->with('product.category')
            ->whereBetween(
                'consumed_at',
                [$inicio7Dias, $finActual]
            )
            ->groupBy('product_id')
            ->orderByDesc('total_consumido')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ACTIVIDAD RECIENTE - DISPONIBILIDAD
        |--------------------------------------------------------------------------
        */

        $actividadEstados = ProductStatusHistory::with([
                'product',
                'user',
            ])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($historial) {

                return [
                    'fecha' => $historial->created_at,

                    'tipo' => 'disponibilidad',

                    'producto' =>
                        $historial->product?->nombre
                        ?? 'Producto eliminado',

                    'detalle' =>
                        $this->nombreEstado(
                            $historial->estado_anterior
                        )
                        . ' → ' .
                        $this->nombreEstado(
                            $historial->estado_nuevo
                        ),

                    'usuario' =>
                        $historial->user?->name
                        ?? 'Usuario no disponible',
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | ACTIVIDAD RECIENTE - CONSUMOS
        |--------------------------------------------------------------------------
        */

        $actividadConsumos = ProductConsumption::with([
                'product',
                'user',
            ])
            ->orderByDesc('consumed_at')
            ->take(5)
            ->get()
            ->map(function ($consumo) {

                return [
                    'fecha' => $consumo->consumed_at,

                    'tipo' => 'consumo',

                    'producto' =>
                        $consumo->product?->nombre
                        ?? 'Producto eliminado',

                    'detalle' =>
                        $consumo->quantity
                        . ' unidad(es) consumida(s)',

                    'usuario' =>
                        $consumo->user?->name
                        ?? 'Usuario no disponible',
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | UNIFICAR ACTIVIDAD
        |--------------------------------------------------------------------------
        */

        $actividadReciente = $actividadEstados
            ->concat($actividadConsumos)
            ->sortByDesc(function ($item) {

                return $item['fecha']->timestamp;

            })
            ->take(5)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | ÚLTIMA ACTUALIZACIÓN
        |--------------------------------------------------------------------------
        */

        $ultimaDisponibilidad =
            ProductStatusHistory::max('created_at');

        $ultimoConsumo =
            ProductConsumption::max('consumed_at');


        $ultimaActualizacion = collect([
                $ultimaDisponibilidad,
                $ultimoConsumo
            ])
            ->filter()
            ->map(function ($fecha) {

                return \Carbon\Carbon::parse($fecha);

            })
            ->sortDesc()
            ->first();


        return view('dashboard', compact(

            'disponibles',
            'bajoStock',
            'agotados',
            'totalProductos',

            'consumoHoy',
            'consumo7Dias',
            'promedioConsumo7Dias',

            'riesgosCriticos',
            'riesgosAltos',
            'topRiesgos',

            'alertasRecomendaciones',

            'labelsConsumo',
            'datosConsumo',

            'platosMasConsumidos',

            'actividadReciente',

            'ultimaActualizacion'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | NOMBRE DEL ESTADO
    |--------------------------------------------------------------------------
    */

    private function nombreEstado($estado): string
    {
        return match ((int) $estado) {
            1 => 'Disponible',
            2 => 'Bajo stock',
            3 => 'Agotado',
            default => 'Sin estado',
        };
    }
}