<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    public function __construct(
        private readonly Collection $reportes
    ) {
    }


    public function collection(): Collection
    {
        return $this->reportes;
    }


    public function headings(): array
    {
        return [
            'ID',
            'Plato',
            'Categoría',
            'Estado actual',
            'Consumo del periodo',
            'Promedio diario',
            'Riesgo actual',
            'Puntaje',
            'Observación',
            'Última actualización',
        ];
    }


    public function map($fila): array
    {
        $producto = $fila['producto'];

        return [

            $producto->id,

            $producto->nombre,

            $producto->category?->nombre
                ?? 'Sin categoría',

            $this->nombreEstado(
                (int) $producto->estado
            ),

            $fila['consumo_periodo'],

            $fila['promedio_periodo'],

            $this->nombreRiesgo(
                $fila['riesgo']
            ),

            $fila['puntaje'] . '/100',

            $producto->observacion
                ?: 'Sin observación',

            $producto->updated_at
                ?->format('d/m/Y H:i'),
        ];
    }


    public function styles(
        Worksheet $sheet
    ): array {

        return [

            1 => [

                'font' => [
                    'bold' => true,
                ],

            ],

        ];
    }


    private function nombreEstado(
        int $estado
    ): string {

        return match ($estado) {

            1 => 'Disponible',

            2 => 'Bajo stock',

            3 => 'Agotado',

            default =>
                'Estado desconocido',
        };
    }


    private function nombreRiesgo(
        string $riesgo
    ): string {

        return $riesgo === 'Critico'
            ? 'Crítico'
            : $riesgo;
    }
}