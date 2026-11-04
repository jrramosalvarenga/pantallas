<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CriticalPathController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\ProspectInteractionController;
use App\Http\Controllers\ScreenController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login'));

Auth::routes(['register' => false]);

Route::get('auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'callback']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clients', ClientController::class);
    Route::resource('screens', ScreenController::class);
    Route::resource('plans', PlanController::class);
    Route::resource('contracts', ContractController::class);
    Route::resource('payments', PaymentController::class);
    Route::post('payments/{payment}/register', [PaymentController::class, 'registerPayment'])->name('payments.register');
    Route::resource('media', MediaController::class);
    Route::post('media/upload', [MediaController::class, 'upload'])->name('media.upload');
    Route::resource('maintenance', MaintenanceController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('incomes', IncomeController::class);
    Route::get('finances', [FinanceController::class, 'index'])->name('finances.index');
    Route::resource('prospects', ProspectController::class);
    Route::post('prospects/{prospect}/interactions', [ProspectInteractionController::class, 'store'])->name('prospects.interactions.store');
    Route::delete('prospects/{prospect}/interactions/{interaction}', [ProspectInteractionController::class, 'destroy'])->name('prospects.interactions.destroy');

    Route::get('critical-path', [CriticalPathController::class, 'index'])->name('critical-path.index');
    Route::post('critical-path/{step}/update-status', [CriticalPathController::class, 'updateStatus'])->name('critical-path.update-status');
});
