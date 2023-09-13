<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\IncomingController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SpendingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LabaKotorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LabaBersihController;
use App\Http\Controllers\RekapModalController;
use App\Http\Controllers\CorporationController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\ProfitSharingController;

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

Route::get('/migrate-fresh-seed', function() {
    Artisan::call('migrate:fresh --seed');
    return 'Success migrate fresh and seed!';
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resources([
        'purchases' => PurchaseController::class,
        'daily-reports' => DailyReportController::class,
        'spendings' => SpendingController::class,

        'corporations' => CorporationController::class,
        'prices' => PriceController::class,
        'shops' => ShopController::class,
        'users' => UserController::class,
    ]);

    Route::get('/daily-reports/{shop_id}/{date}/detail', [DailyReportController::class, 'detail'])->name('daily-reports.detail');

    Route::get('/daily-reports/create/shop-data', [DailyReportController::class, 'getShopData'])->name('daily-reports.shop-data');

    Route::get('/shops/{shop}/investors', [ShopController::class, 'investor'])->name('shops.investors');
    Route::post('/shops/{shop}/investors', [ShopController::class, 'investorStore'])->name('shops.investors.store');
    Route::put('/shops/{shop}/investors', [ShopController::class, 'investorUpdate'])->name('shops.investors.update');
    Route::delete('/shops/{shop}/investors', [ShopController::class, 'investorDestroy'])->name('shops.investors.destroy');

    // Route::prefix('spendings')->group(function () {
    //     Route::get('/', [SpendingController::class, 'index'])->name('spendings.index');
    //     Route::get('/create', [SpendingController::class, 'create'])->name('spendings.create');
    //     Route::get('/{shop_id}/{year_month}', [SpendingController::class, 'edit'])->name('spendings.edit');
    // });

    Route::prefix('laba-kotor')->group(function () {
        Route::get('/', [LabaKotorController::class, 'index'])->name('laba-kotor.index');
        Route::get('/{shop_id}/{year_month}', [LabaKotorController::class, 'edit'])->name('laba-kotor.edit');
    });

    Route::prefix('laba-bersih')->group(function () {
        Route::get('/', [LabaBersihController::class, 'index'])->name('laba-bersih.index');
        Route::get('/{shop_id}/{year_month}', [LabaBersihController::class, 'edit'])->name('laba-bersih.edit');
        Route::post('/{shop_id}/{year_month}/alokasi_modal', [LabaBersihController::class, 'alokasi_modal'])->name('laba-bersih.alokasi-modal');
    });

    Route::prefix('rekap-modal')->group(function () {
        Route::get('/', [RekapModalController::class, 'index'])->name('rekap-modal.index');
        Route::post('/store', [RekapModalController::class, 'store'])->name('rekap-modal.store');
        Route::delete('/{id}', [RekapModalController::class, 'destroy'])->name('rekap-modal.destroy');
        Route::get('/{shop_id}/{year_month}', [RekapModalController::class, 'edit'])->name('rekap-modal.edit');
    });

    Route::prefix('profit-sharing')->group(function () {
        Route::get('/', [ProfitSharingController::class, 'index'])->name('profit-sharing.index');
        Route::get('/{shop_id}/{year_month}', [ProfitSharingController::class, 'edit'])->name('profit-sharing.edit');
    });
});
