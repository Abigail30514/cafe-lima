<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsReportExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    public function __construct(
        private readonly ?int $categoria = null,
        private readonly ?int $estado = null,
        private readonly ?string $fechaInicio = null,
        private readonly ?string $fechaFin = null
    ) {
    }

    public function query(): Builder
    {
        return Product::query()
            ->with('category')
            ->when(
                $this->categoria,
                fn (Builder $query) =>
                    $query->where('category_id', $this->categoria)
            )
            ->when(
                $this->estado,
                fn (Builder $query) =>
                    $query->where('estado', $this->estado)
            )
            ->when(
                $this->fechaInicio,
                fn (Builder $query) =>
                    $query->whereDate('updated_at', '>=', $this->fechaInicio)
            )
            ->when(
                $this->fechaFin,
                fn (Builder $query) =>
                    $query->whereDate('updated_at', '<=', $this->fechaFin)
            )
            ->orderBy('nombre');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Producto',
            'Categoría',
            'Estado actual',
            'Observación',
            'Última actualización',
        ];
    }

    public function map($producto): array
    {
        return [
            $producto->id,
            $producto->nombre,
            $producto->category?->nombre ?? 'Sin categoría',
            $this->nombreEstado((int) $producto->estado),
            $producto->observacion ?: 'Sin observación',
            $producto->updated_at?->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    private function nombreEstado(int $estado): string
    {
        return match ($estado) {
            1 => 'Disponible',
            2 => 'Bajo stock',
            3 => 'Agotado',
            default => 'Estado desconocido',
        };
    }
}