<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductConsumption;

class StockoutRiskService
{
    public function calculate(Product $product): array
    {
        /*
        |--------------------------------------------------------------------------
        | PERIODOS DE ANÁLISIS
        |--------------------------------------------------------------------------
        */

        $inicioActual = now()
            ->subDays(6)
            ->startOfDay();

        $finActual = now()->endOfDay();

        $inicioAnterior = now()
            ->subDays(13)
            ->startOfDay();

        $finAnterior = now()
            ->subDays(7)
            ->endOfDay();


        /*
        |--------------------------------------------------------------------------
        | CONSUMO ACTUAL
        |--------------------------------------------------------------------------
        */

        $consumoActual = ProductConsumption::where(
                'product_id',
                $product->id
            )
            ->whereBetween(
                'consumed_at',
                [$inicioActual, $finActual]
            )
            ->sum('quantity');


        /*
        |--------------------------------------------------------------------------
        | CONSUMO DEL PERIODO ANTERIOR
        |--------------------------------------------------------------------------
        */

        $consumoAnterior = ProductConsumption::where(
                'product_id',
                $product->id
            )
            ->whereBetween(
                'consumed_at',
                [$inicioAnterior, $finAnterior]
            )
            ->sum('quantity');


        /*
        |--------------------------------------------------------------------------
        | PROMEDIO DIARIO
        |--------------------------------------------------------------------------
        */

        $promedioDiario = round(
            $consumoActual / 7,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | TENDENCIA
        |--------------------------------------------------------------------------
        */

        if ($consumoAnterior > 0) {

            $tendencia = round(
                (($consumoActual - $consumoAnterior)
                    / $consumoAnterior) * 100,
                1
            );

        } elseif ($consumoActual > 0) {

            // Existe consumo reciente, pero no había consumo anterior.
            $tendencia = null;

        } else {

            $tendencia = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | SI ESTÁ AGOTADO → CRÍTICO
        |--------------------------------------------------------------------------
        */

        if ((int) $product->estado === 3) {

            return [
                'consumo_actual' => $consumoActual,
                'consumo_anterior' => $consumoAnterior,
                'promedio_diario' => $promedioDiario,
                'tendencia' => $tendencia,
                'puntaje' => 100,
                'nivel' => 'Critico',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | PUNTAJE POR ESTADO
        |--------------------------------------------------------------------------
        */

        $puntajeEstado = match ((int) $product->estado) {
            2 => 50,
            default => 0,
        };


        /*
        |--------------------------------------------------------------------------
        | PUNTAJE POR PROMEDIO DIARIO
        |--------------------------------------------------------------------------
        */

        if ($promedioDiario >= 5) {

            $puntajeConsumo = 25;

        } elseif ($promedioDiario >= 3) {

            $puntajeConsumo = 20;

        } elseif ($promedioDiario >= 1.5) {

            $puntajeConsumo = 12;

        } elseif ($promedioDiario > 0) {

            $puntajeConsumo = 5;

        } else {

            $puntajeConsumo = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | PUNTAJE POR TENDENCIA
        |--------------------------------------------------------------------------
        */

        if ($tendencia === null) {

            // Sin periodo anterior suficiente para comparar.
            $puntajeTendencia = $consumoActual > 0 ? 10 : 0;

        } elseif ($tendencia > 50) {

            $puntajeTendencia = 25;

        } elseif ($tendencia > 20) {

            $puntajeTendencia = 18;

        } elseif ($tendencia > 0) {

            $puntajeTendencia = 10;

        } else {

            $puntajeTendencia = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | PUNTAJE TOTAL
        |--------------------------------------------------------------------------
        */

        $puntaje = min(
            100,
            $puntajeEstado
            + $puntajeConsumo
            + $puntajeTendencia
        );


        /*
        |--------------------------------------------------------------------------
        | CLASIFICACIÓN DEL RIESGO
        |--------------------------------------------------------------------------
        */

        if ($puntaje >= 70) {

            $nivel = 'Critico';

        } elseif ($puntaje >= 50) {

            $nivel = 'Alto';

        } elseif ($puntaje >= 30) {

            $nivel = 'Medio';

        } else {

            $nivel = 'Bajo';
        }


        return [
            'consumo_actual' => $consumoActual,
            'consumo_anterior' => $consumoAnterior,
            'promedio_diario' => $promedioDiario,
            'tendencia' => $tendencia,
            'puntaje' => $puntaje,
            'nivel' => $nivel,
        ];
    }

    public function recommendation(Product $product, array $risk): array
    {
        /*
        |--------------------------------------------------------------------------
        | PRODUCTO AGOTADO
        |--------------------------------------------------------------------------
        */

        if ((int) $product->estado === 3) {

            return [
                'alerta' => 'Reposición inmediata',
                'recomendacion' =>
                    'El plato se encuentra agotado. Se recomienda realizar la reposición inmediatamente antes de continuar ofreciendo el producto.',
                'prioridad' => 'Critica',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | RIESGO CRÍTICO
        |--------------------------------------------------------------------------
        */

        if ($risk['nivel'] === 'Critico') {

            return [
                'alerta' => 'Riesgo crítico de agotamiento',
                'recomendacion' =>
                    'El consumo reciente y el estado actual indican un riesgo crítico. Se recomienda priorizar la reposición del plato.',
                'prioridad' => 'Critica',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | RIESGO ALTO
        |--------------------------------------------------------------------------
        */

        if ($risk['nivel'] === 'Alto') {

            return [
                'alerta' => 'Riesgo alto de agotamiento',
                'recomendacion' =>
                    'El plato presenta un nivel elevado de consumo o bajo stock. Se recomienda preparar una reposición preventiva.',
                'prioridad' => 'Alta',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | RIESGO MEDIO
        |--------------------------------------------------------------------------
        */

        if ($risk['nivel'] === 'Medio') {

            return [
                'alerta' => 'Consumo en aumento',
                'recomendacion' =>
                    'Se recomienda mantener seguimiento del plato y preparar una posible reposición si continúa la tendencia de consumo.',
                'prioridad' => 'Media',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | RIESGO BAJO
        |--------------------------------------------------------------------------
        */

        return [
            'alerta' => null,
            'recomendacion' =>
                'El comportamiento actual no requiere una reposición preventiva.',
            'prioridad' => 'Baja',
        ];
    }
}