<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LandingButtonController;
use App\Http\Controllers\Admin\LinkedRestaurantController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Language Switcher
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Password Reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard - للجميع
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // =============================================
    // Routes للأدمن فقط
    // =============================================
    Route::middleware(['admin'])->group(function () {
        // إنشاء مطعم جديد
        Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
        Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
        
        // حذف مطعم
        Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy'])->name('restaurants.destroy');
        
        // Staff Management - للأدمن فقط
        Route::resource('staff', StaffController::class)->except(['show']);
        Route::post('/staff/{staff}/toggle', [StaffController::class, 'toggleStatus'])->name('staff.toggle');
    });
    
    // =============================================
    // Routes للمطاعم (مع فحص الصلاحية)
    // =============================================
    Route::middleware(['restaurant.access'])->group(function () {
        // عرض وتعديل المطعم
        Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');
        Route::get('/restaurants/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('restaurants.edit');
        Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');
        Route::post('/restaurants/{restaurant}/upload-image', [RestaurantController::class, 'uploadImage'])->name('restaurants.upload-image');
        Route::delete('/restaurants/{restaurant}/delete-image/{type}', [RestaurantController::class, 'deleteImage'])->name('restaurants.delete-image');
        Route::get('/restaurants/{restaurant}/qrcode', [RestaurantController::class, 'qrCode'])->name('restaurants.qrcode');
        Route::get('/restaurants/{restaurant}/copy', [RestaurantController::class, 'copyMenu'])->name('restaurants.copy');
        Route::post('/restaurants/{restaurant}/copy', [RestaurantController::class, 'doCopyMenu'])->name('restaurants.copy.do');
        
        // Restaurant Categories (nested)
        Route::get('/restaurants/{restaurant}/categories/create', [CategoryController::class, 'create'])->name('restaurants.categories.create');
        Route::post('/restaurants/{restaurant}/categories', [CategoryController::class, 'store'])->name('restaurants.categories.store');
        Route::get('/restaurants/{restaurant}/categories/{category}/edit', [CategoryController::class, 'edit'])->name('restaurants.categories.edit');
        Route::put('/restaurants/{restaurant}/categories/{category}', [CategoryController::class, 'update'])->name('restaurants.categories.update');
        Route::delete('/restaurants/{restaurant}/categories/{category}', [CategoryController::class, 'destroy'])->name('restaurants.categories.destroy');
        Route::post('/restaurants/{restaurant}/categories/reorder', [CategoryController::class, 'reorder'])->name('restaurants.categories.reorder');
        
        // Restaurant Items (nested)
        Route::get('/restaurants/{restaurant}/items/create', [MenuItemController::class, 'create'])->name('restaurants.items.create');
        Route::post('/restaurants/{restaurant}/items', [MenuItemController::class, 'store'])->name('restaurants.items.store');
        Route::get('/restaurants/{restaurant}/items/{item}/edit', [MenuItemController::class, 'edit'])->name('restaurants.items.edit');
        Route::put('/restaurants/{restaurant}/items/{item}', [MenuItemController::class, 'update'])->name('restaurants.items.update');
        Route::delete('/restaurants/{restaurant}/items/{item}', [MenuItemController::class, 'destroy'])->name('restaurants.items.destroy');
        Route::post('/restaurants/{restaurant}/items/{item}/toggle-availability', [MenuItemController::class, 'toggleAvailability'])->name('restaurants.items.toggle-availability');
        Route::post('/restaurants/{restaurant}/items/{item}/toggle-featured', [MenuItemController::class, 'toggleFeatured'])->name('restaurants.items.toggle-featured');
        Route::post('/restaurants/{restaurant}/items/reorder', [MenuItemController::class, 'reorder'])->name('restaurants.items.reorder');
        
        // Restaurant Social Links
        Route::put('/restaurants/{restaurant}/social', [SocialLinkController::class, 'updateForRestaurant'])->name('restaurants.social.update');

        // Landing Buttons
        Route::get('/restaurants/{restaurant}/landing-buttons', [LandingButtonController::class, 'index'])->name('landing-buttons.index');
        Route::put('/restaurants/{restaurant}/landing-buttons/{button}', [LandingButtonController::class, 'update'])->name('landing-buttons.update');
        Route::post('/restaurants/{restaurant}/landing-buttons/reorder', [LandingButtonController::class, 'reorder'])->name('landing-buttons.reorder');
        Route::patch('/restaurants/{restaurant}/landing-buttons/{button}/toggle', [LandingButtonController::class, 'toggle'])->name('landing-buttons.toggle');

        // Linked Restaurants
        Route::get('/restaurants/{restaurant}/linked', [LinkedRestaurantController::class, 'index'])->name('linked-restaurants.index');
        Route::post('/restaurants/{restaurant}/linked', [LinkedRestaurantController::class, 'link'])->name('linked-restaurants.link');
        Route::delete('/restaurants/{restaurant}/linked/{linked}', [LinkedRestaurantController::class, 'unlink'])->name('linked-restaurants.unlink');
        Route::patch('/restaurants/{restaurant}/linked/toggle', [LinkedRestaurantController::class, 'toggleSelector'])->name('linked-restaurants.toggle');
    });
    
    // قائمة المطاعم - للأدمن فقط
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index')->middleware('admin');
    
    // إعدادات النظام - للأدمن فقط
    Route::middleware('admin')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings/general', [SettingController::class, 'updateGeneral'])->name('settings.general');
        Route::put('/settings/appearance', [SettingController::class, 'updateAppearance'])->name('settings.appearance');
    });
});

// Public Menu Routes (يجب أن تكون في النهاية)
Route::get('/{slug}', [MenuController::class, 'landing'])->name('menu.landing');
Route::get('/{slug}/menu', [MenuController::class, 'show'])->name('menu.show');
Route::get('/{slug}/menu/pdf', [MenuController::class, 'showPdf'])->name('menu.pdf');
Route::get('/{slug}/menu/digital', [MenuController::class, 'showDigital'])->name('menu.digital');
Route::get('/{slug}/branches', [MenuController::class, 'branches'])->name('menu.branches');
Route::post('/{slug}/language', [MenuController::class, 'switchLanguage'])->name('menu.language');