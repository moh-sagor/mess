<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\MealPreferenceController;
use App\Http\Controllers\BazaarRecordController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CostSharingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Meal Management
    Route::resource('meals', MealController::class);
    
    // Meal Preferences
    Route::resource('meal-preferences', MealPreferenceController::class);
    
    // Bazaar Records (Manager and Admin)
    Route::middleware('role:manager|admin')->group(function () {
        Route::resource('bazaar-records', BazaarRecordController::class);
    });
    
    // Expenses (all users can view, admin/manager can manage)
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    
    Route::middleware('role:admin|manager')->group(function () {
        Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::get('expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    });
    
    Route::get('expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    
    // Expense approval (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::patch('expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
        Route::patch('expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    });
    
    // Cost Sharing (all users can view, admin/manager can manage)
    Route::get('cost-sharing', [CostSharingController::class, 'index'])->name('cost-sharing.index');
    Route::get('cost-sharing-summary', [CostSharingController::class, 'summary'])->name('cost-sharing.summary');
    
    Route::middleware('role:admin|manager')->group(function () {
        Route::get('cost-sharing/create', [CostSharingController::class, 'create'])->name('cost-sharing.create');
        Route::post('cost-sharing/calculate', [CostSharingController::class, 'calculate'])->name('cost-sharing.calculate');
        Route::delete('cost-sharing/{costSharing}', [CostSharingController::class, 'destroy'])->name('cost-sharing.destroy');
    });
    
    Route::get('cost-sharing/{costSharing}', [CostSharingController::class, 'show'])->name('cost-sharing.show');
    
    // Cost sharing status updates (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::patch('cost-sharing/{costSharing}/status', [CostSharingController::class, 'updateStatus'])->name('cost-sharing.update-status');
        Route::post('cost-sharing/bulk-status', [CostSharingController::class, 'bulkUpdateStatus'])->name('cost-sharing.bulk-status');
    });
});

require __DIR__.'/auth.php';
