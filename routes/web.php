<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\GoalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/transactions/scan', [TransactionController::class, 'scan'])->name('transactions.scan');
    Route::resource('transactions', TransactionController::class);
    Route::get('/transactions-export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::resource('budgets', BudgetController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('goals', GoalController::class)->except(['create', 'edit', 'show']);
    Route::post('/goals/{goal}/adjust', [GoalController::class, 'adjust'])->name('goals.adjust');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
