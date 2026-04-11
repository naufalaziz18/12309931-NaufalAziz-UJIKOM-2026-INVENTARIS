<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LendingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;

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

// --- AUTH AREA ---
Route::middleware(['auth'])->group(function () {

    // Taro tepat di bawah middleware auth
    Route::middleware(['auth'])->group(function () {

        // RUTE UTAMA (Static ditaro di atas)
        Route::get('/admin/items/create', [ProductController::class, 'create'])->name('products.create');
        Route::get('/admin/items', [ProductController::class, 'adminIndex'])->name('admin.items.index');

        // RUTE DYNAMIC (Ditaro di bawah)
        Route::get('/admin/items/{product}', [ProductController::class, 'show'])->name('products.show');
    });

    // LOGOUT
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
    // Pastikan ini ditaruh DI ATAS rute yang ada {product}
    Route::get('/admin/items/create', [ProductController::class, 'create'])->name('products.create');

    // Rute lainnya
    Route::get('/admin/items', [ProductController::class, 'adminIndex'])->name('admin.items.index');
    Route::post('/admin/items', [ProductController::class, 'store'])->name('products.store');
    Route::get('/admin/items/{product}', [ProductController::class, 'show'])->name('products.show');

    // 1. EXPORT GLOBAL (WAJIB DI ATAS RESOURCE)
    Route::get('/products/export-all', [ProductController::class, 'exportAllExcel'])->name('products.export.all');
    Route::get('/products/{id}/export', [ProductController::class, 'exportExcel'])->name('products.export');

    // 2. DASHBOARD & DETAILS
    Route::get('/dashboard', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/lendings', [ProductController::class, 'lendingDetails'])->name('products.lendings.details');

    // 3. PRODUCT ACTIONS
    Route::post('/products/{product}/borrow', [ProductController::class, 'borrow'])->name('products.borrow');
    Route::post('/products/{product}/return', [ProductController::class, 'return'])->name('products.return');

    // 4. CRUD PRODUCTS (Resource Generik)
    Route::resource('products', ProductController::class)->except(['index']);

    // --- AREA ADMIN ---
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {

        // Rute Admin untuk Produk (Alias: products.admin.)
        Route:: as('products.admin.')->group(function () {
            Route::get('/dashboard', [ProductController::class, 'adminIndex'])->name('index');
            Route::get('/items', [ProductController::class, 'adminIndex'])->name('items.index');
            Route::get('/items/export-all', [ProductController::class, 'exportAllExcel'])->name('items.export.all');



            Route::resource('categories', CategoryController::class);
            Route::resource('items', ProductController::class)->parameters(['items' => 'product'])->except(['index']);
            Route::post('/products/reset-stock', [ProductController::class, 'resetStock'])->name('reset');
        });

        Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {

            // --- USER MANAGEMENT (ORDER SAKTI) ---

            // 1. Rute Export (Pake URL unik biar gak bentrok sama ID /users/{id})
            Route::get('/users-export-data', [UserController::class, 'exportExcel'])->name('users.export');

            // 2. Rute List Operator
            // URL: /admin/users/export (TARUH PALING ATAS BIAR GAK BIGINT)
            // Tambahin {role} di ujung URL
            Route::get('/users/export/{role}', [UserController::class, 'exportExcel'])->name('users.export');// PALING ATAS
            Route::get('/users/list-operator', [UserController::class, 'indexOperator'])->name('users.operator');
            Route::resource('users', UserController::class);

            // 3. Resource Users (Manual biar kontrol penuh)
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

            // --- CATEGORIES & ITEMS ---
            Route::get('/categories/export', [CategoryController::class, 'exportExcel'])->name('categories.export');
            Route::resource('categories', CategoryController::class);
            Route::resource('items', ProductController::class)->parameters(['items' => 'product'])->except(['index']);
        });


        // Bagian User Management
        Route:: as('admin.')->group(function () {
            Route::resource('users', UserController::class);

            // Tambahkan alias manual supaya nama 'operator.users.index' dikenali
            // Ini untuk nampilin daftar user yang rolenya operator
            Route::get('/users/operator', [UserController::class, 'indexOperator'])->name('users.index.operator');
            // Items Management (Ini yang bikin error tadi)
            Route::get('/items', [ProductController::class, 'adminIndex'])->name('items.index');
            Route::resource('items', ProductController::class)->parameters(['items' => 'product'])->except(['index']);
        });


        // FIX: Tambahkan alias ini supaya link 'operator.users.index' di sidebar/blade lo nggak error
        Route::get('/admin/users/operator-list', [UserController::class, 'indexOperator'])->name('operator.users.index');

        Route::get('/categories/export', [CategoryController::class, 'exportExcel'])->name('categories.export');

        Route::resource('categories', CategoryController::class);

        Route::get('/users', [UserController::class, 'indexAdmin'])->name('users.index');
        Route::resource('users', UserController::class)->except(['index']);
    });
});

// --- AREA OPERATOR ---
Route::prefix('operator')->as('operator.')->group(function () {
    Route::get('/borrow', [ProductController::class, 'borrowIndex'])->name('borrow.index');
    Route::get('/borrow/create', [ProductController::class, 'borrowForm'])->name('borrow.create');
    Route::post('/borrow/store', [ProductController::class, 'processBorrow'])->name('borrow.store');
    // Sesuaikan nama controller yang lo pake
    Route::post('/operator/borrow/store', [PeminjamanController::class, 'store'])->name('operator.borrow.store');
    Route::patch('/borrow/{borrow}/return', [ProductController::class, 'processReturn'])->name('borrow.return');
    Route::get('/borrow/export', [ProductController::class, 'exportExcel'])->name('borrow.export');
});

// PROFILE
Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
Route::match(['patch', 'put'], '/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');


Route::get('/operators-management', [UserController::class, 'indexOperator'])->name('users.operator');
