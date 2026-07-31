<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStatusHistory;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function index(): View
    {
        $historiales = ProductStatusHistory::with([
                'product.category',
                'user',
            ])
            ->when(request('buscar'), function ($query, $buscar) {
                $query->whereHas('product', function ($productQuery) use ($buscar) {
                    $productQuery->where(
                        'nombre',
                        'like',
                        '%' . $buscar . '%'
                    );
                });
            })
            ->when(request('estado'), function ($query, $estado) {
                $query->where('estado_nuevo', $estado);
            })
            ->when(request('fecha'), function ($query, $fecha) {
                $query->whereDate('created_at', $fecha);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $productos = Product::orderBy('nombre')->get();

        return view('historial.index', compact(
            'historiales',
            'productos'
        ));
    }
}