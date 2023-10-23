<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LabaKotorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LabaBersihController;
use App\Http\Controllers\RekapModalController;
use App\Http\Controllers\CorporationController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\ProfitSharingController;
use App\Http\Controllers\SpendingController;

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

Route::get('/migrate-fresh-seed', function () {
    Artisan::call('migrate:fresh --seed');
    return 'Success migrate fresh and seed!';
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('spendings', SpendingController::class);

    Route::resource('purchases', PurchaseController::class)->middleware(['role:super-admin,admin']);
    //route daily reports
    Route::prefix('daily-reports')->middleware(['role:super-admin,admin,operator'])->group(function () {
        Route::get('/', [DailyReportController::class, 'index'])->name('daily-reports.index');
        Route::get('/{dailyReport}/edit', [DailyReportController::class, 'edit'])->name('daily-reports.edit');
        Route::put('/{dailyReport}', [DailyReportController::class, 'update'])->name('daily-reports.update');
        Route::delete('/{dailyReport}', [DailyReportController::class, 'destroy'])->name('daily-reports.destroy');
        Route::get('/create/shop-data', [DailyReportController::class, 'getShopData'])->name('daily-reports.shop-data');
        Route::get('/{shop_id}/{date}/detail', [DailyReportController::class, 'detail'])->name('daily-reports.detail');
    });

    Route::prefix('daily-reports')->middleware(['role:super-admin,admin,operator'])->group(function () {
        Route::get('/create', [DailyReportController::class, 'create'])->name('daily-reports.create');
        Route::post('/', [DailyReportController::class, 'store'])->name('daily-reports.store');
    });
    Route::middleware(['role:super-admin'])->group(function () {

        Route::prefix('master')->group(function () {
            Route::resources([
                'corporations' => CorporationController::class,
                'prices' => PriceController::class,
                'shops' => ShopController::class,
                'users' => UserController::class,
                'vendors' => VendorController::class,
                'documents' => DocumentController::class,
            ]);
        });

        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

        Route::prefix('master/shops')->group(function () {
            Route::get('/{shop}/documents', [ShopController::class, 'documents'])->name('shops.documents');
            Route::get('/{shop}/investors', [ShopController::class, 'investor'])->name('shops.investors');
            Route::post('/{shop}/investors', [ShopController::class, 'investorStore'])->name('shops.investors.store');
            Route::put('/{shop}/investors', [ShopController::class, 'investorUpdate'])->name('shops.investors.update');
            Route::delete('/{shop}/investors', [ShopController::class, 'investorDestroy'])->name('shops.investors.destroy');
        });
    });

    Route::prefix('laba-kotor')->middleware(['role:super-admin,admin,investor'])->group(function () {
        Route::get('/', [LabaKotorController::class, 'index'])->name('laba-kotor.index');
        Route::get('/{shop_id}/{year_month}', [LabaKotorController::class, 'edit'])->name('laba-kotor.edit');
    });

    Route::prefix('laba-bersih')->group(function () {
        Route::middleware(['role:super-admin,admin,investor'])->group(function () {
            Route::get('/', [LabaBersihController::class, 'index'])->name('laba-bersih.index');
            Route::get('/{shop_id}/{year_month}', [LabaBersihController::class, 'edit'])->name('laba-bersih.edit');
        });
        Route::middleware(['role:super-admin,admin'])->group(function () {
            Route::post('/store', [LabaBersihController::class, 'store'])->name('laba-bersih.store');
            Route::put('/{labaBersih}', [LabaBersihController::class, 'update'])->name('laba-bersih.update');
            Route::delete('/{labaBersih}', [LabaBersihController::class, 'destroy'])->name('laba-bersih.destroy');
        });
    });

    Route::prefix('rekap-modal')->group(function () {
        Route::middleware(['role:super-admin,admin,investor'])->group(function () {
            Route::get('/', [RekapModalController::class, 'index'])->name('rekap-modal.index');
            Route::get('/{shop_id}/{year_month}', [RekapModalController::class, 'edit'])->name('rekap-modal.edit');
        });
        Route::middleware(['role:super-admin,admin'])->group(function () {
            Route::post('/store', [RekapModalController::class, 'store'])->name('rekap-modal.store');
            Route::delete('/{rekapModal}', [RekapModalController::class, 'destroy'])->name('rekap-modal.destroy');
            Route::put('/{rekapModal}', [RekapModalController::class, 'update'])->name('rekap-modal.update');
        });
    });

    Route::prefix('profit-sharing')->middleware(['role:super-admin,admin,investor'])->group(function () {
        Route::get('/', [ProfitSharingController::class, 'index'])->name('profit-sharing.index');
    });
});
