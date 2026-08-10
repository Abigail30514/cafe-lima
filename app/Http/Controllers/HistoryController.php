<?php

namespace App\Http\Controllers;

use App\Models\ProductConsumption;
use App\Models\ProductStatusHistory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function index(): View
    {
        $buscar = request('buscar');
        $tipo = request('tipo');
        $estado = request('estado');
        $fecha = request('fecha');

        /*
        |--------------------------------------------------------------------------
        | HISTORIAL DE DISPONIBILIDAD
        |--------------------------------------------------------------------------
        */

        $historialEstados = collect();

        if (!$tipo || $tipo === 'disponibilidad') {

            $historialEstados = ProductStatusHistory::with([
                    'product.category',
                    'user',
                ])
                ->when($buscar, function ($query) use ($buscar) {
                    $query->whereHas('product', function ($productQuery) use ($buscar) {
                        $productQuery->where(
                            'nombre',
                            'like',
                            '%' . $buscar . '%'
                        );
                    });
                })
                ->when($estado, function ($query) use ($estado) {
                    $query->where('estado_nuevo', $estado);
                })
                ->when($fecha, function ($query) use ($fecha) {
                    $query->whereDate('created_at', $fecha);
                })
                ->get()
                ->map(function ($historial) {

                    return [
                        'fecha' => $historial->created_at,
                        'tipo' => 'disponibilidad',
                        'producto' => $historial->product?->nombre
                            ?? 'Producto eliminado',
                        'categoria' => $historial->product?->category?->nombre
                            ?? 'Sin categoría',
                        'estado_anterior' => $historial->estado_anterior,
                        'estado_nuevo' => $historial->estado_nuevo,
                        'cantidad' => null,
                        'usuario' => $historial->user?->name
                            ?? 'Usuario no disponible',
                        'observacion' => $historial->observacion,
                    ];
                });
        }

        /*
        |--------------------------------------------------------------------------
        | HISTORIAL DE CONSUMOS
        |--------------------------------------------------------------------------
        */

        $historialConsumos = collect();

        if (!$tipo || $tipo === 'consumo') {

            $historialConsumos = ProductConsumption::with([
                    'product.category',
                    'user',
                ])
                ->when($buscar, function ($query) use ($buscar) {
                    $query->whereHas('product', function ($productQuery) use ($buscar) {
                        $productQuery->where(
                            'nombre',
                            'like',
                            '%' . $buscar . '%'
                        );
                    });
                })
                ->when($fecha, function ($query) use ($fecha) {
                    $query->whereDate('consumed_at', $fecha);
                })
                ->get()
                ->map(function ($consumo) {

                    return [
                        'fecha' => $consumo->consumed_at,
                        'tipo' => 'consumo',
                        'producto' => $consumo->product?->nombre
                            ?? 'Producto eliminado',
                        'categoria' => $consumo->product?->category?->nombre
                            ?? 'Sin categoría',
                        'estado_anterior' => null,
                        'estado_nuevo' => null,
                        'cantidad' => $consumo->quantity,
                        'usuario' => $consumo->user?->name
                            ?? 'Usuario no disponible',
                        'observacion' => $consumo->observation,
                    ];
                });
        }

        /*
        |--------------------------------------------------------------------------
        | UNIFICAR Y ORDENAR
        |--------------------------------------------------------------------------
        */

        $movimientos = $historialEstados
            ->concat($historialConsumos)
            ->sortByDesc('fecha')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | PAGINACIÓN
        |--------------------------------------------------------------------------
        */

        $paginaActual = LengthAwarePaginator::resolveCurrentPage();
        $porPagina = 10;

        $historiales = new LengthAwarePaginator(
            $movimientos
                ->forPage($paginaActual, $porPagina)
                ->values(),
            $movimientos->count(),
            $porPagina,
            $paginaActual,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('historial.index', compact('historiales'));
    }
}