<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    public function index(): View
    {
        $productos = Product::with('category')
            ->when(request('buscar'), function ($query, $buscar) {
                $query->where('nombre', 'like', '%' . $buscar . '%');
            })
            ->when(request('categoria'), function ($query, $categoria) {
                $query->where('category_id', $categoria);
            })
            ->when(request('estado'), function ($query, $estado) {
                $query->where('estado', $estado);
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        $categorias = Category::orderBy('nombre')->get();

        return view('disponibilidad.index', compact(
            'productos',
            'categorias'
        ));
    }
}