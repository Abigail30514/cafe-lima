<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\ProductStatusHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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

            ->orderBy('id')
            ->get();

        $categorias = Category::orderBy('nombre')->get();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function store(
        StoreProductRequest $request
    ): RedirectResponse {
        $datos = $request->validated();

        $datos['destacado'] = $request->boolean('destacado');

        Product::create($datos);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    public function update(
        UpdateProductRequest $request,
        Product $producto
    ): RedirectResponse {
        $datos = $request->validated();

        $datos['destacado'] = $request->boolean('destacado');

        $estadoAnterior = $producto->estado;

        DB::transaction(function () use (
            $producto,
            $datos,
            $estadoAnterior
        ) {
            $producto->update($datos);

            if ($estadoAnterior != $producto->estado) {
                ProductStatusHistory::create([
                    'product_id' => $producto->id,
                    'user_id' => Auth::id(),
                    'estado_anterior' => $estadoAnterior,
                    'estado_nuevo' => $producto->estado,
                    'observacion' => $producto->observacion,
                ]);
            }
        });

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function actualizarEstado(
        Product $producto
    ): RedirectResponse {
        $datos = request()->validate([
            'estado' => ['required', 'integer', 'in:1,2,3'],
        ]);

        $estadoAnterior = $producto->estado;
        $estadoNuevo = (int) $datos['estado'];

        if ($estadoAnterior === $estadoNuevo) {
            return back()
                ->with('success', 'El producto ya tenía el estado seleccionado.');
        }

        DB::transaction(function () use (
            $producto,
            $estadoAnterior,
            $estadoNuevo
        ) {
            $producto->update([
                'estado' => $estadoNuevo,
            ]);

            ProductStatusHistory::create([
                'product_id' => $producto->id,
                'user_id' => Auth::id(),
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $estadoNuevo,
                'observacion' => $producto->observacion,
            ]);
        });

        return back()
            ->with('success', 'Disponibilidad actualizada correctamente.');
    }
}