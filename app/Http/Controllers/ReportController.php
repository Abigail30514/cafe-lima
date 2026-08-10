<?php

namespace App\Http\Controllers;

use App\Exports\ProductsReportExport;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductConsumption;
use App\Services\StockoutRiskService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REPORTE EN PANTALLA
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        StockoutRiskService $riskService
    ): View {

        $datos = $this->validarFiltros($request);

        [$fechaInicio, $fechaFin] =
            $this->resolverFechas($datos);

        $reportes = $this->construirReporte(
            $datos['categoria'] ?? null,
            $datos['estado'] ?? null,
            $fechaInicio,
            $fechaFin,
            $riskService
        );

        $categorias = Category::orderBy('nombre')->get();

        /*
        |--------------------------------------------------------------------------
        | INDICADORES
        |--------------------------------------------------------------------------
        */

        $totalProductos = $reportes->count();

        $totalConsumo = $reportes
            ->sum('consumo_periodo');

        $disponibles = $reportes
            ->filter(
                fn ($item) =>
                    (int) $item['producto']->estado === 1
            )
            ->count();

        $bajoStock = $reportes
            ->filter(
                fn ($item) =>
                    (int) $item['producto']->estado === 2
            )
            ->count();

        $agotados = $reportes
            ->filter(
                fn ($item) =>
                    (int) $item['producto']->estado === 3
            )
            ->count();

        $riesgosAltosCriticos = $reportes
            ->filter(function ($item) {

                return in_array(
                    $item['riesgo'],
                    ['Alto', 'Critico']
                );

            })
            ->count();


        return view(
            'reportes.index',
            compact(
                'reportes',
                'categorias',
                'fechaInicio',
                'fechaFin',
                'totalProductos',
                'totalConsumo',
                'disponibles',
                'bajoStock',
                'agotados',
                'riesgosAltosCriticos'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORTAR EXCEL
    |--------------------------------------------------------------------------
    */

    public function exportExcel(
        Request $request,
        StockoutRiskService $riskService
    ): BinaryFileResponse {

        $datos = $this->validarFiltros($request);

        [$fechaInicio, $fechaFin] =
            $this->resolverFechas($datos);

        $reportes = $this->construirReporte(
            $datos['categoria'] ?? null,
            $datos['estado'] ?? null,
            $fechaInicio,
            $fechaFin,
            $riskService
        );

        $nombreArchivo =
            'reporte_operativo_cafe_lima_' .
            now()->format('Y-m-d_H-i-s') .
            '.xlsx';

        return Excel::download(
            new ProductsReportExport($reportes),
            $nombreArchivo
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORTAR PDF
    |--------------------------------------------------------------------------
    */

    public function exportPdf(
        Request $request,
        StockoutRiskService $riskService
    ) {

        $datos = $this->validarFiltros($request);

        [$fechaInicio, $fechaFin] =
            $this->resolverFechas($datos);

        $reportes = $this->construirReporte(
            $datos['categoria'] ?? null,
            $datos['estado'] ?? null,
            $fechaInicio,
            $fechaFin,
            $riskService
        );

        $totalProductos = $reportes->count();

        $totalConsumo = $reportes
            ->sum('consumo_periodo');

        $disponibles = $reportes
            ->filter(
                fn ($item) =>
                    (int) $item['producto']->estado === 1
            )
            ->count();

        $bajoStock = $reportes
            ->filter(
                fn ($item) =>
                    (int) $item['producto']->estado === 2
            )
            ->count();

        $agotados = $reportes
            ->filter(
                fn ($item) =>
                    (int) $item['producto']->estado === 3
            )
            ->count();

        $riesgosAltosCriticos = $reportes
            ->filter(function ($item) {

                return in_array(
                    $item['riesgo'],
                    ['Alto', 'Critico']
                );

            })
            ->count();


        $categoriaSeleccionada = null;

        if (!empty($datos['categoria'])) {

            $categoriaSeleccionada = Category::find(
                $datos['categoria']
            )?->nombre;
        }


        $pdf = Pdf::loadView(
            'reportes.pdf',
            compact(
                'reportes',
                'fechaInicio',
                'fechaFin',
                'totalProductos',
                'totalConsumo',
                'disponibles',
                'bajoStock',
                'agotados',
                'riesgosAltosCriticos',
                'categoriaSeleccionada',
                'datos'
            )
        );

        $pdf->setPaper('a4', 'landscape');

        $nombreArchivo =
            'reporte_operativo_cafe_lima_' .
            now()->format('Y-m-d_H-i-s') .
            '.pdf';

        return $pdf->download($nombreArchivo);
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR FILTROS
    |--------------------------------------------------------------------------
    */

    private function validarFiltros(
        Request $request
    ): array {

        return $request->validate([

            'categoria' => [
                'nullable',
                'integer',
                'exists:categories,id'
            ],

            'estado' => [
                'nullable',
                'integer',
                'in:1,2,3'
            ],

            'fecha_inicio' => [
                'nullable',
                'date'
            ],

            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio'
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | FECHAS
    |--------------------------------------------------------------------------
    | Si el usuario no selecciona fechas, se analizan los últimos 7 días.
    |--------------------------------------------------------------------------
    */

    private function resolverFechas(
        array $datos
    ): array {

        $fechaInicio =
            $datos['fecha_inicio']
            ?? now()
                ->subDays(6)
                ->toDateString();

        $fechaFin =
            $datos['fecha_fin']
            ?? now()
                ->toDateString();

        return [
            $fechaInicio,
            $fechaFin
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | CONSTRUIR REPORTE
    |--------------------------------------------------------------------------
    */

    private function construirReporte(
        ?int $categoria,
        ?int $estado,
        string $fechaInicio,
        string $fechaFin,
        StockoutRiskService $riskService
    ): Collection {

        $inicio = Carbon::parse(
            $fechaInicio
        )->startOfDay();

        $fin = Carbon::parse(
            $fechaFin
        )->endOfDay();


        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS
        |--------------------------------------------------------------------------
        */

        $productos = Product::with('category')

            ->when(
                $categoria,
                fn ($query) =>
                    $query->where(
                        'category_id',
                        $categoria
                    )
            )

            ->when(
                $estado,
                fn ($query) =>
                    $query->where(
                        'estado',
                        $estado
                    )
            )

            ->orderBy('nombre')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CONSUMO DEL PERIODO
        |--------------------------------------------------------------------------
        */

        $consumos = ProductConsumption::query()

            ->select('product_id')

            ->selectRaw(
                'SUM(quantity) AS total_consumido'
            )

            ->whereBetween(
                'consumed_at',
                [$inicio, $fin]
            )

            ->whereIn(
                'product_id',
                $productos->pluck('id')
            )

            ->groupBy('product_id')

            ->pluck(
                'total_consumido',
                'product_id'
            );


        $diasPeriodo =
            max(
                1,
                (int) $inicio
                    ->copy()
                    ->startOfDay()
                    ->diffInDays(
                        $fin
                            ->copy()
                            ->startOfDay()
                    ) + 1
            );


        /*
        |--------------------------------------------------------------------------
        | INTEGRACIÓN: DISPONIBILIDAD + CONSUMO + RIESGO
        |--------------------------------------------------------------------------
        */

        return $productos->map(
            function ($producto) use (
                $consumos,
                $diasPeriodo,
                $riskService
            ) {

                $consumoPeriodo =
                    (int) (
                        $consumos[$producto->id]
                        ?? 0
                    );


                $risk =
                    $riskService->calculate(
                        $producto
                    );


                return [

                    'producto' =>
                        $producto,

                    'consumo_periodo' =>
                        $consumoPeriodo,

                    'promedio_periodo' =>
                        round(
                            $consumoPeriodo
                            / $diasPeriodo,
                            2
                        ),

                    /*
                    | Riesgo actual calculado por HU-11.
                    */

                    'riesgo' =>
                        $risk['nivel'],

                    'puntaje' =>
                        $risk['puntaje'],
                ];
            }
        );
    }
}