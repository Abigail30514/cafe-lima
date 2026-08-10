<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\StockoutRiskService;
use Illuminate\View\View;

class StockoutRiskController extends Controller
{
    public function index(
        StockoutRiskService $riskService
    ): View {

        $products = Product::with('category')
            ->orderBy('nombre')
            ->get()
            ->map(function ($product) use ($riskService) {

                $risk = $riskService->calculate($product);

                return [
                    'product' => $product,

                    'consumo_actual' =>
                        $risk['consumo_actual'],

                    'consumo_anterior' =>
                        $risk['consumo_anterior'],

                    'promedio_diario' =>
                        $risk['promedio_diario'],

                    'tendencia' =>
                        $risk['tendencia'],

                    'puntaje' =>
                        $risk['puntaje'],

                    'nivel' =>
                        $risk['nivel'],
                ];
            })
            ->sortByDesc('puntaje')
            ->values();

        return view(
            'riesgo-agotamiento.index',
            compact('products')
        );
    }
}