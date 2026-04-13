<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Inv-Pro System (Clean Version)
|--------------------------------------------------------------------------
*/

// --- GUEST AREA ---
Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [ProductController::class, 'login'])->name('login.post');
});

// --- AUTH AREA (Login Required) ---
Route::middleware(['auth'])->group(function () {

    // 1. LOGOUT
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // 2. PROFILE MANAGEMENT
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::match(['patch', 'put'], '/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    // 3. GENERAL PRODUCT ACTIONS (User Biasa/Dashboard)
    Route::get('/dashboard', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/export-all', [ProductController::class, 'exportAllExcel'])->name('products.export.all');
    Route::get('/products/{id}/export', [ProductController::class, 'exportExcel'])->name('products.export');
    Route::get('/products/{product}/lendings', [ProductController::class, 'lendingDetails'])->name('products.lendings.details');
    Route::get('/products/{id}/export-pdf', [ProductController::class, 'exportProductLendingPdf'])->name('products.exportLendingPdf');
    Route::post('/products/{product}/borrow', [ProductController::class, 'borrow'])->name('products.borrow');
    Route::post('/products/{product}/return', [ProductController::class, 'return'])->name('products.return');

    // Resource Products (Kecuali index karena pakai dashboard di atas)
    Route::resource('products', ProductController::class)->except(['index']);

    // --- AREA ADMIN (Role: Admin) ---
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {

        // A. ITEM & CATEGORY MANAGEMENT (Admin View)
        Route:: as('admin.')->group(function () {
            // Statis ditaruh di atas
            Route::get('/items/export-all', [ProductController::class, 'exportAllExcel'])->name('items.export.all');
            Route::get('/items', [ProductController::class, 'adminIndex'])->name('items.index');
            Route::post('/items/reset-stock', [ProductController::class, 'resetStock'])->name('items.reset');
            Route::get('/items/export-pdf', [ProductController::class, 'exportPdf'])->name('items.export.pdf');

            // Resource Items & Categories
            Route::resource('items', ProductController::class)->parameters(['items' => 'product'])->except(['index']);

            Route::get('/categories/export', [CategoryController::class, 'exportExcel'])->name('categories.export');
            Route::get('/categories/export-pdf', [CategoryController::class, 'exportPdf'])->name('categories.export-pdf');
            
            Route::resource('categories', CategoryController::class);
        });

        // B. USER MANAGEMENT (Order Sakti)
        Route:: as('admin.')->group(function () {
            // 1. Export & List khusus (Taruh di atas agar tidak dianggap ID)
            Route::get('/users/export/{role}', [UserController::class, 'exportExcel'])->name('users.export');
            Route::get('/users/operator-list', [UserController::class, 'indexOperator'])->name('users.operator');
            Route::get('/users/operator-index', [UserController::class, 'indexOperator'])->name('users.index.operator');
            Route::get('/users/export/{role}', [UserController::class, 'exportExcel'])->name('admin.users.export');
            Route::get('/users/export-pdf/{role}', [UserController::class, 'exportPdf'])->name('admin.users.export-pdf');
            // 3. Main User Resource
            Route::get('/users', [UserController::class, 'indexAdmin'])->name('users.index');
            Route::resource('users', UserController::class)->except(['index']);
        });

        // C. PRODUCT ADMIN ALIAS (Sesuai request lo sebelumnya)
        Route:: as('products.admin.')->group(function () {
            Route::get('/dashboard-admin', [ProductController::class, 'adminIndex'])->name('index');
            Route::get('/items-admin', [ProductController::class, 'adminIndex'])->name('items.index');
        });
    });

    // --- AREA OPERATOR (Peminjaman) ---
    Route::prefix('operator')->as('operator.')->group(function () {
        Route::get('/borrow', [ProductController::class, 'borrowIndex'])->name('borrow.index');
        Route::get('/borrow/create', [ProductController::class, 'borrowForm'])->name('borrow.create');
        Route::post('/borrow/store', [ProductController::class, 'processBorrow'])->name('borrow.store');
        Route::post('/borrow/peminjaman', [PeminjamanController::class, 'store'])->name('borrow.store.alt');
        Route::patch('/borrow/{borrow}/return', [PeminjamanController::class, 'return'])->name('borrow.return');
        Route::get('/borrow/export', [ProductController::class, 'exportExcel'])->name('borrow.export');
        Route::get('/borrow/export-pdf', [ProductController::class, 'exportBorrowPdf'])->name('borrow.exportPdf');  

    });

});

// --- GLOBAL ALIAS (Supaya rute lama tetap jalan) ---
Route::get('/operators-management', [UserController::class, 'indexOperator'])->name('users.operator');