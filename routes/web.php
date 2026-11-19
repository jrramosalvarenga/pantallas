<?php

use App\Http\Controllers\AccountingController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientTaskController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CriticalPathController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
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
Route::get('/landingpage', fn () => view('landingpage'))->name('landingpage');
Route::get('/promo', fn () => view('promo'))->name('promo');

Auth::routes(['register' => false]);

Route::get('auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'callback']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin,vendedor')->group(function () {
        Route::resource('prospects', ProspectController::class);
        Route::post('prospects/{prospect}/upload-video', [ProspectController::class, 'uploadVideo'])->name('prospects.upload-video');
        Route::delete('prospects/{prospect}/delete-video', [ProspectController::class, 'deleteVideo'])->name('prospects.delete-video');
        Route::get('prospects/{prospect}/convert', [ProspectController::class, 'convertForm'])->name('prospects.convert');
        Route::post('prospects/{prospect}/convert', [ProspectController::class, 'convert'])->name('prospects.convert.store');
        Route::post('prospects/{prospect}/interactions', [ProspectInteractionController::class, 'store'])->name('prospects.interactions.store');
        Route::delete('prospects/{prospect}/interactions/{interaction}', [ProspectInteractionController::class, 'destroy'])->name('prospects.interactions.destroy');
    });

    Route::middleware('role:admin,socia,operador')->group(function () {
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
        Route::get('critical-path', [CriticalPathController::class, 'index'])->name('critical-path.index');
        Route::post('critical-path/{step}/update-status', [CriticalPathController::class, 'updateStatus'])->name('critical-path.update-status');

        Route::resource('tasks', ClientTaskController::class);
        Route::post('tasks/{task}/update-status', [ClientTaskController::class, 'updateStatus'])->name('tasks.update-status');

        Route::resource('events', EventController::class);

        Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

        Route::get('accounting', [AccountingController::class, 'index'])->name('accounting.index');
        Route::get('accounting/client/{client}', [AccountingController::class, 'clientStatement'])->name('accounting.client-statement');
    });
});
