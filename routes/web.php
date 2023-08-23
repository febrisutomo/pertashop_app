<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\IncomingController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TestPumpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'authenticate'])->name('authenticate');
Route::delete('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resources([
        'sales' => SaleController::class,
        'purchases' => PurchaseController::class,
        'incomings' => IncomingController::class,
        'test-pumps' => TestPumpController::class,
    ]);

    Route::prefix('data')->group(function () {
        Route::resources([
            'operators' => OperatorController::class,
        ]);
    });
    Route::prefix('reports')->group(function () {
        Route::prefix('bulanan')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/{shop_id}/{month}', [ReportController::class, 'show'])->name('reports.show');
            Route::get('/laba-kotor', [ReportController::class, 'laba_kotor'])->name('reports.laba_kotor');
        });
    });
});
