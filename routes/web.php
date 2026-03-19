<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RegisterShopController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ==============================
// مسارات تسجيل الدخول (عامة)
// ==============================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // تسجيل محل جديد
    Route::get('/register-shop', [RegisterShopController::class, 'showRegistrationForm'])->name('register.shop');
    Route::post('/register-shop', [RegisterShopController::class, 'register'])->name('register.shop.post');
});

// تسجيل الخروج
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==============================
// المسارات المحمية
// ==============================
Route::middleware(['auth', 'shop_active'])->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // المخزون
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);

    // العمليات
    Route::resource('purchases', PurchaseController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('expenses', ExpenseController::class);

    // الأطراف
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);

    // التقارير
    Route::get('/reports/profit', [ReportController::class, 'profit'])->name('reports.profit');
    Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');

    // الملف الشخصي
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // API للـ Ajax
    Route::get('/api/product/{id}', [ProductController::class, 'getProductData'])->name('api.product');

    // ==================================
    // لوحة تحكم المطور (Super Admin)
    // ==================================
    Route::middleware('super_admin')->prefix('developer')->name('super_admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdminController::class, 'index'])->name('index');
        Route::post('/shop/{shop}/toggle', [\App\Http\Controllers\SuperAdminController::class, 'toggleStatus'])->name('toggle');
        Route::get('/shop/{shop}/edit', [\App\Http\Controllers\SuperAdminController::class, 'editShop'])->name('edit');
        Route::put('/shop/{shop}', [\App\Http\Controllers\SuperAdminController::class, 'updateShop'])->name('update');
        Route::get('/settings', [\App\Http\Controllers\SuperAdminController::class, 'developerSettings'])->name('settings');
    });
});
