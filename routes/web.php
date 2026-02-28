<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\ProductVariantController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;

// Home Pública
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas de Autenticación (sin protección)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Logout (protegido)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Dashboard del Super Admin
    Route::middleware('role:super-admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Gestión de Categorías (solo super admin)
        Route::resource('categories', CategoryController::class);
    });

    // Rutas de Vendedor
    Route::middleware('role:vendedor')->prefix('seller')->name('seller.')->group(function () {
        Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
        
        // Gestión de Productos (vendedores y super admin)
        Route::resource('products', ProductController::class);
        
        // Eliminar imagen de producto
        Route::delete('/products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.delete-image');
        
        // Gestión de Variantes
        Route::resource('products/{product}/variants', ProductVariantController::class, [
            'names' => [
                'index' => 'variants.index',
                'create' => 'variants.create',
                'store' => 'variants.store',
                'edit' => 'variants.edit',
                'update' => 'variants.update',
                'destroy' => 'variants.destroy',
            ]
        ]);
    });

    // Dashboard del Cliente
    Route::middleware('role:cliente')->prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    });

    // Dashboard genérico (redirige según rol)
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

