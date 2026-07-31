<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStatusHistory;
use Illuminate\View\View;
use App\Exports\ProductsReportExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        $productos = Product::with('category')
            ->when(request('categoria'), function ($query, $categoria) {
                $query->where('category_id', $categoria);
            })
            ->when(request('estado'), function ($query, $estado) {
                $query->where('estado', $estado);
            })

            ->when(request('fecha_inicio'), function ($query, $fechaInicio) {
                $query->whereDate('updated_at', '>=', $fechaInicio);
            })
            ->when(request('fecha_fin'), function ($query, $fechaFin) {
                $query->whereDate('updated_at', '<=', $fechaFin);
            })
            ->orderBy('nombre')
            ->get();

        $categorias = Category::orderBy('nombre')->get();

        $totalProductos = $productos->count();
        $disponibles = $productos->where('estado', 1)->count();
        $bajoStock = $productos->where('estado', 2)->count();
        $agotados = $productos->where('estado', 3)->count();

        $totalCambios = ProductStatusHistory::when(
            request('fecha_inicio'),
            function ($query, $fechaInicio) {
                $query->whereDate('created_at', '>=', $fechaInicio);
            }
        )
        ->when(
            request('fecha_fin'),
            function ($query, $fechaFin) {
                $query->whereDate('created_at', '<=', $fechaFin);
            }
        )
        ->count();

        return view('reportes.index', compact(
            'productos',
            'categorias',
            'totalProductos',
            'disponibles',
            'bajoStock',
            'agotados',
            'totalCambios'
        ));
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $datos = $request->validate([
            'categoria' => ['nullable', 'integer', 'exists:categories,id'],
            'estado' => ['nullable', 'integer', 'in:1,2,3'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio',
            ],
        ]);

        $nombreArchivo =
            'reporte_disponibilidad_' .
            now()->format('Y-m-d_H-i-s') .
            '.xlsx';

        return Excel::download(
            new ProductsReportExport(
                isset($datos['categoria'])
                    ? (int) $datos['categoria']
                    : null,

                isset($datos['estado'])
                    ? (int) $datos['estado']
                    : null,

                $datos['fecha_inicio'] ?? null,
                $datos['fecha_fin'] ?? null
            ),
            $nombreArchivo
        );
    }

    public function exportPdf(Request $request)
    {
        $datos = $request->validate([
            'categoria' => ['nullable', 'integer', 'exists:categories,id'],
            'estado' => ['nullable', 'integer', 'in:1,2,3'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio',
            ],
        ]);

        $productos = Product::with('category')
            ->when(
                $datos['categoria'] ?? null,
                function ($query, $categoria) {
                    $query->where('category_id', $categoria);
                }
            )
            ->when(
                $datos['estado'] ?? null,
                function ($query, $estado) {
                    $query->where('estado', $estado);
                }
            )
            ->when(
                $datos['fecha_inicio'] ?? null,
                function ($query, $fechaInicio) {
                    $query->whereDate(
                        'updated_at',
                        '>=',
                        $fechaInicio
                    );
                }
            )
            ->when(
                $datos['fecha_fin'] ?? null,
                function ($query, $fechaFin) {
                    $query->whereDate(
                        'updated_at',
                        '<=',
                        $fechaFin
                    );
                }
            )
            ->orderBy('nombre')
            ->get();

        $totalProductos = $productos->count();
        $disponibles = $productos->where('estado', 1)->count();
        $bajoStock = $productos->where('estado', 2)->count();
        $agotados = $productos->where('estado', 3)->count();

        $pdf = Pdf::loadView('reportes.pdf', compact(
            'productos',
            'totalProductos',
            'disponibles',
            'bajoStock',
            'agotados',
            'datos'
        ));

        $pdf->setPaper('a4', 'landscape');

        $nombreArchivo =
            'reporte_disponibilidad_' .
            now()->format('Y-m-d_H-i-s') .
            '.pdf';

        return $pdf->download($nombreArchivo);
    }
}