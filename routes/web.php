<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\SellerRegistrationController;
use App\Http\Controllers\SellerApprovalController;
use App\Http\Controllers\SellerDashboardController;
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

// === RUTAS PÚBLICAS DE REGISTRO DE VENDEDOR ===
Route::get('/seller/register', [SellerRegistrationController::class, 'showRegistrationForm'])->name('seller.register.form');
Route::post('/seller/register', [SellerRegistrationController::class, 'register'])->name('seller.register');
Route::get('/seller/registration-pending', [SellerRegistrationController::class, 'showPending'])->name('seller.registration.pending');
Route::get('/seller/registration/status', [SellerRegistrationController::class, 'checkStatus'])->name('seller.registration.status')->middleware('auth');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // === RUTAS DEL ADMIN - APROBACIÓN DE VENDEDORES ===
    Route::middleware('role:super-admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Gestión de Categorías (solo super admin)
        Route::resource('categories', CategoryController::class);
        
        // Gestión de Vendedores
        Route::prefix('sellers')->name('sellers.')->group(function () {
            Route::get('/', [SellerApprovalController::class, 'index'])->name('index');
            Route::get('/{user}', [SellerApprovalController::class, 'show'])->name('show');
            Route::patch('/{user}/approve', [SellerApprovalController::class, 'approve'])->name('approve');
            Route::patch('/{user}/reject', [SellerApprovalController::class, 'reject'])->name('reject');
            Route::patch('/{user}/suspend', [SellerApprovalController::class, 'suspend'])->name('suspend');
            Route::patch('/{user}/reactivate', [SellerApprovalController::class, 'reactivate'])->name('reactivate');
            Route::patch('/{user}/commission', [SellerApprovalController::class, 'updateCommission'])->name('update-commission');
        });
    });

    // === RUTAS DEL VENDEDOR (APROBADO) ===
    Route::middleware('role:vendedor')->prefix('seller')->name('seller.')->group(function () {
        // Panel y comisiones
        Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/commissions', [SellerDashboardController::class, 'commissions'])->name('commissions');
        Route::get('/commissions/{commission}', [SellerDashboardController::class, 'commissionDetail'])->name('commission.detail');
        Route::get('/payouts', [SellerDashboardController::class, 'payouts'])->name('payouts');
        Route::get('/payouts/{payout}', [SellerDashboardController::class, 'payoutDetail'])->name('payout.detail');
        Route::get('/profile', [SellerDashboardController::class, 'profile'])->name('profile');
        Route::patch('/profile', [SellerDashboardController::class, 'updateProfile'])->name('profile.update');
        
        // Gestión de Productos
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

