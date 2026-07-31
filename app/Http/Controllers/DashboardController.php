<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStatusHistory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $disponibles = Product::where('estado', 1)->count();
        $bajoStock = Product::where('estado', 2)->count();
        $agotados = Product::where('estado', 3)->count();

        $totalProductos = $disponibles + $bajoStock + $agotados;

        /*
        |--------------------------------------------------------------------------
        | Alertas importantes
        |--------------------------------------------------------------------------
        | Primero aparecen los productos recomendados agotados,
        | luego los agotados y finalmente los de bajo stock.
        */
        $productosCriticos = Product::with('category')
            ->whereIn('estado', [2, 3])
            ->orderByDesc('destacado')
            ->orderByDesc('estado')
            ->orderBy('nombre')
            ->take(8)
            ->get();

        $totalAlertasCriticas = Product::where('estado', 3)
            ->where('destacado', true)
            ->count();

        $ultimosCambios = ProductStatusHistory::with([
                'product',
                'user',
            ])
            ->latest()
            ->take(5)
            ->get();

        $ultimaActualizacion = ProductStatusHistory::max('created_at');

        return view('dashboard', compact(
            'disponibles',
            'bajoStock',
            'agotados',
            'totalProductos',
            'productosCriticos',
            'totalAlertasCriticas',
            'ultimosCambios',
            'ultimaActualizacion'
        ));
    }
}