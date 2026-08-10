<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\StockoutRiskService;
use Illuminate\View\View;

class ReplenishmentAlertController extends Controller
{
    public function index(
        StockoutRiskService $riskService
    ): View {

        $alertas = Product::with('category')
            ->orderBy('nombre')
            ->get()
            ->map(function ($product) use ($riskService) {

                $risk = $riskService->calculate($product);

                $recommendation = $riskService->recommendation(
                    $product,
                    $risk
                );

                return [
                    'product' => $product,

                    'consumo_actual' =>
                        $risk['consumo_actual'],

                    'promedio_diario' =>
                        $risk['promedio_diario'],

                    'tendencia' =>
                        $risk['tendencia'],

                    'puntaje' =>
                        $risk['puntaje'],

                    'nivel' =>
                        $risk['nivel'],

                    'alerta' =>
                        $recommendation['alerta'],

                    'recomendacion' =>
                        $recommendation['recomendacion'],

                    'prioridad' =>
                        $recommendation['prioridad'],
                ];
            })

            /*
            |--------------------------------------------------------------------------
            | MOSTRAR SOLO PRODUCTOS QUE REQUIEREN ATENCIÓN
            |--------------------------------------------------------------------------
            */

            ->filter(function ($item) {
                return $item['puntaje'] >= 30;
            })

            ->sortByDesc('puntaje')
            ->values();


        $criticas = $alertas
            ->where('prioridad', 'Critica')
            ->count();

        $altas = $alertas
            ->where('prioridad', 'Alta')
            ->count();

        $medias = $alertas
            ->where('prioridad', 'Media')
            ->count();


        return view(
            'alertas-reposicion.index',
            compact(
                'alertas',
                'criticas',
                'altas',
                'medias'
            )
        );
    }
}