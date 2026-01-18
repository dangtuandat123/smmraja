<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// SEO routes
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Landing Pages (SEO)
Route::prefix('dich-vu')->group(function () {
    Route::get('/tang-follow-instagram', [\App\Http\Controllers\LandingPageController::class, 'instagram'])->name('landing.instagram');
    Route::get('/mua-like-facebook', [\App\Http\Controllers\LandingPageController::class, 'facebook'])->name('landing.facebook');
    Route::get('/tang-view-tiktok', [\App\Http\Controllers\LandingPageController::class, 'tiktok'])->name('landing.tiktok');
    Route::get('/tang-view-youtube', [\App\Http\Controllers\LandingPageController::class, 'youtube'])->name('landing.youtube');
    Route::get('/smm-panel', [\App\Http\Controllers\LandingPageController::class, 'smmPanel'])->name('landing.smm-panel');
});

// Blog routes
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// Guest routes (only for non-authenticated users)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
    // Orders
    Route::get('/order', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/order', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/refill', [OrderController::class, 'refill'])->name('orders.refill');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    
    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/history', [WalletController::class, 'history'])->name('wallet.history');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // API endpoints for getting service details
    Route::get('/api/services/{service}', [ServiceController::class, 'show'])->name('api.services.show');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [\App\Http\Controllers\Admin\StatisticController::class, 'index'])->name('statistics.index');
    
    // Categories
    Route::resource('categories', AdminCategoryController::class);
    
    // Services
    Route::get('/services/import', [AdminServiceController::class, 'import'])->name('services.import');
    Route::post('/services/import', [AdminServiceController::class, 'doImport'])->name('services.doImport');
    Route::post('/services/sync-prices', [AdminServiceController::class, 'syncPrices'])->name('services.syncPrices');
    Route::resource('services', AdminServiceController::class);
    
    // Orders
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
    Route::post('/orders/check-status', [AdminOrderController::class, 'checkStatus'])->name('orders.checkStatus');
    Route::get('/orders/refunds', [AdminOrderController::class, 'refunds'])->name('orders.refunds');
    Route::post('/orders/{order}/approve-refund', [AdminOrderController::class, 'approveRefund'])->name('orders.approveRefund');
    Route::post('/orders/{order}/reject-refund', [AdminOrderController::class, 'rejectRefund'])->name('orders.rejectRefund');
    
    // Users
    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/adjust-balance', [AdminUserController::class, 'adjustBalance'])->name('users.adjustBalance');
    
    // Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::get('/settings/test-telegram', [AdminSettingController::class, 'testTelegram'])->name('settings.test-telegram');
    
    // API Balance check
    Route::get('/api-balance', [AdminDashboardController::class, 'apiBalance'])->name('api.balance');
    
    // Exchange rate
    Route::get('/exchange-rate', [AdminDashboardController::class, 'exchangeRate'])->name('exchangeRate');
    Route::post('/exchange-rate/refresh', [AdminDashboardController::class, 'refreshExchangeRate'])->name('exchangeRate.refresh');
    
    // Blog Posts
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
});
