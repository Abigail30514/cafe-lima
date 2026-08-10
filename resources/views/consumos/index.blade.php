@extends('layouts.app')

@section('title', 'Registro de Consumos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Registro de Consumos</h2>
        <p class="text-muted mb-0">
            Registra la salida o consumo de platos durante la operación.
        </p>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>No se pudo registrar el consumo.</strong>

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-plus-circle me-2"></i>
            Nuevo registro
        </h5>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('consumos.store') }}">
            @csrf

            <div class="row g-3">

                <div class="col-md-5">

                    <label class="form-label fw-semibold">
                        Plato
                    </label>

                    <select
                        name="product_id"
                        class="form-select"
                        required
                    >
                        <option value="">
                            Seleccione un plato
                        </option>

                        @foreach($products as $product)

                            <option
                                value="{{ $product->id }}"
                                {{ old('product_id') == $product->id ? 'selected' : '' }}
                            >
                                {{ $product->nombre }}
                                @if($product->category)
                                    - {{ $product->category->nombre }}
                                @endif
                            </option>

                        @endforeach
                    </select>

                </div>


                <div class="col-md-2">

                    <label class="form-label fw-semibold">
                        Cantidad
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        min="1"
                        value="{{ old('quantity', 1) }}"
                        class="form-control"
                        required
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Fecha
                    </label>

                    <input
                        type="date"
                        name="consumed_date"
                        value="{{ old('consumed_date', now()->timezone('America/Lima')->format('Y-m-d')) }}"
                        class="form-control"
                        required
                    >

                </div>

                <div class="col-md-2">

                    <label class="form-label fw-semibold">
                        Hora
                    </label>

                    <input
                        type="time"
                        name="consumed_time"
                        value="{{ old('consumed_time', now()->timezone('America/Lima')->format('H:i')) }}"
                        class="form-control"
                        required
                    >

                </div>


                <div class="col-12">

                    <label class="form-label fw-semibold">
                        Observación
                    </label>

                    <textarea
                        name="observation"
                        class="form-control"
                        rows="2"
                        maxlength="255"
                        placeholder="Observación opcional..."
                    >{{ old('observation') }}</textarea>

                </div>


                <div class="col-12 text-end">

                    <button
                        type="submit"
                        class="btn btn-primary px-4"
                    >
                        <i class="bi bi-check-circle me-2"></i>
                        Registrar consumo
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-white py-3">

        <div class="d-flex justify-content-between align-items-center">

            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-clock-history me-2"></i>
                Últimos consumos registrados
            </h5>

            <span class="badge bg-secondary">
                {{ $consumptions->total() }} registros
            </span>

        </div>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th>Plato</th>
                        <th>Categoría</th>
                        <th class="text-center">Cantidad</th>
                        <th>Fecha y hora</th>
                        <th>Registrado por</th>
                        <th>Observación</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($consumptions as $consumption)

                        <tr>

                            <td class="fw-semibold">
                                {{ $consumption->product->nombre }}
                            </td>

                            <td>
                                {{ $consumption->product->category->nombre ?? '-' }}
                            </td>

                            <td class="text-center">

                                <span class="badge bg-primary rounded-pill">
                                    {{ $consumption->quantity }}
                                </span>

                            </td>

                            <td>
                                {{ $consumption->consumed_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                {{ $consumption->user?->name ?? 'Usuario eliminado' }}
                            </td>

                            <td>
                                {{ $consumption->observation ?: '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-5"
                            >
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                                Aún no existen consumos registrados.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    @if($consumptions->hasPages())

        <div class="card-footer bg-white">
            {{ $consumptions->links() }}
        </div>

    @endif

</div>

@endsection