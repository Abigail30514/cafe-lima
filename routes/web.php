<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductConsumptionController;
use App\Http\Controllers\ConsumptionAnalysisController;
use App\Http\Controllers\StockoutRiskController;
use App\Http\Controllers\ReplenishmentAlertController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Todos los usuarios autenticados
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get(
        '/disponibilidad',
        [AvailabilityController::class, 'index']
    )->name('disponibilidad.index');


    /*
    |--------------------------------------------------------------------------
    | Administrador y Cocina
    |--------------------------------------------------------------------------
    */

    Route::middleware('rol:1,2')->group(function () {

        Route::patch(
            '/productos/{producto}/estado',
            [ProductController::class, 'actualizarEstado']
        )->name('productos.estado');

        Route::get(
            '/historial',
            [HistoryController::class, 'index']
        )->name('historial.index');

        // HU-08 - Registro de consumo
        Route::get(
            '/consumos',
            [ProductConsumptionController::class, 'index']
        )->name('consumos.index');

        Route::post(
            '/consumos',
            [ProductConsumptionController::class, 'store']
        )->name('consumos.store');
        Route::get(
            '/analisis-consumo',
            [ConsumptionAnalysisController::class, 'index']
        )->name('analisis-consumo.index');
        Route::get(
            '/riesgo-agotamiento',
            [StockoutRiskController::class, 'index']
        )->name('riesgo-agotamiento.index');
        Route::get(
            '/alertas-reposicion',
            [ReplenishmentAlertController::class, 'index']
        )->name('alertas-reposicion.index');

    });


    /*
    |--------------------------------------------------------------------------
    | Solo Administrador
    |--------------------------------------------------------------------------
    */

    Route::middleware('rol:1')->group(function () {

        Route::resource('usuarios', UserController::class);

        Route::resource('categorias', CategoryController::class);

        Route::resource('productos', ProductController::class);

        Route::get(
            '/reportes',
            [ReportController::class, 'index']
        )->name('reportes.index');

        Route::get(
            '/reportes/excel',
            [ReportController::class, 'exportExcel']
        )->name('reportes.excel');

        Route::get(
            '/reportes/pdf',
            [ReportController::class, 'exportPdf']
        )->name('reportes.pdf');

    });

});

require __DIR__.'/auth.php';