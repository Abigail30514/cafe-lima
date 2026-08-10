<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductConsumption;
use Illuminate\Http\Request;

class ProductConsumptionController extends Controller
{
    public function index()
    {
        $consumptions = ProductConsumption::with(['product.category', 'user'])
            ->orderByDesc('consumed_at')
            ->paginate(15);

        $products = Product::with('category')
            ->orderBy('nombre')
            ->get();

        return view('consumos.index', compact('consumptions', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'consumed_date' => ['required', 'date'],
            'consumed_time' => ['required', 'date_format:H:i'],
            'observation' => ['nullable', 'string', 'max:255'],
        ]);

        $consumedAt = $validated['consumed_date'] . ' ' . $validated['consumed_time'];

        ProductConsumption::create([
            'product_id' => $validated['product_id'],
            'user_id' => auth()->id(),
            'quantity' => $validated['quantity'],
            'consumed_at' => $consumedAt,
            'observation' => $validated['observation'] ?? null,
        ]);

        return redirect()
            ->route('consumos.index')
            ->with('success', 'Consumo registrado correctamente.');
    }
}