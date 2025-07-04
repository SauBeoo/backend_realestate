<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AiChatController;
use App\Http\Controllers\Admin\AuthController;

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

Route::get('/', function () {
    return view('welcome');
});

// Admin Authentication Routes (Guest only)
Route::prefix('admin')->name('admin.')->middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Admin routes (protected)
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
    Route::get('/dashboard/real-time-updates', [DashboardController::class, 'getRealTimeUpdates'])->name('dashboard.real-time-updates');
    Route::post('/dashboard/refresh-cache', [DashboardController::class, 'refreshCache'])->name('dashboard.refresh-cache');
    Route::get('/dashboard/analytics-by-date', [DashboardController::class, 'getAnalyticsByDateRange'])->name('dashboard.analytics-by-date');
    Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');
    Route::get('/dashboard/download-export', function() { 
        return response()->json(['message' => 'Export download would be handled here']); 
    })->name('dashboard.download-export');

    // Property Management
    Route::resource('properties', PropertyController::class);

    // User Management
    Route::resource('users', UserController::class);
    Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
    Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');

    // Analytics & Reports
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/property-report', [AnalyticsController::class, 'propertyReport'])->name('property-report');
        Route::get('/user-report', [AnalyticsController::class, 'userReport'])->name('user-report');
        Route::get('/financial-report', [AnalyticsController::class, 'financialReport'])->name('financial-report');
        Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
        Route::get('/real-time-data', [AnalyticsController::class, 'realTimeData'])->name('real-time-data');
    });

    // AI Chat Management
    Route::prefix('ai-chat')->name('ai-chat.')->group(function () {
        Route::get('/', [AiChatController::class, 'index'])->name('index');
        Route::get('/services', [AiChatController::class, 'services'])->name('services');
        Route::put('/services/{serviceId}', [AiChatController::class, 'updateService'])->name('services.update');
        Route::get('/analytics', [AiChatController::class, 'analytics'])->name('analytics');
        Route::get('/sessions', [AiChatController::class, 'sessions'])->name('sessions');
        Route::get('/sessions/{sessionId}', [AiChatController::class, 'viewSession'])->name('sessions.show');
        Route::post('/test-service', [AiChatController::class, 'testService'])->name('test-service');
        Route::get('/export', [AiChatController::class, 'export'])->name('export');
    });

    // System Settings & Management
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SystemController::class, 'index'])->name('index');
        Route::get('/settings', [App\Http\Controllers\Admin\SystemController::class, 'settings'])->name('settings');
        Route::put('/settings', [App\Http\Controllers\Admin\SystemController::class, 'updateSettings'])->name('settings.update');
        Route::get('/integrations', [App\Http\Controllers\Admin\SystemController::class, 'integrations'])->name('integrations');
        Route::put('/integrations', [App\Http\Controllers\Admin\SystemController::class, 'updateIntegrations'])->name('integrations.update');
        Route::get('/maintenance', [App\Http\Controllers\Admin\SystemController::class, 'maintenance'])->name('maintenance');
        Route::post('/maintenance', [App\Http\Controllers\Admin\SystemController::class, 'performMaintenance'])->name('maintenance.perform');
        Route::get('/security', [App\Http\Controllers\Admin\SystemController::class, 'security'])->name('security');
        Route::get('/export-config', [App\Http\Controllers\Admin\SystemController::class, 'exportConfig'])->name('export-config');
    });
});
