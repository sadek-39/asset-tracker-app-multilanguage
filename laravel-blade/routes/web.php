<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomeSourceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AssetController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Income Module
    Route::get('/income', [IncomeSourceController::class, 'index'])->name('income.index');
    Route::post('/income-sources', [IncomeSourceController::class, 'store'])->name('income.store');
    Route::post('/income-transactions', [TransactionController::class, 'storeIncome'])->name('income.transaction.store');
    
    Route::get('/expenses', [TransactionController::class, 'expenseIndex'])->name('expenses.index');
    Route::post('/expenses', [TransactionController::class, 'storeExpense'])->name('expenses.store');
    
    Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
    Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
});
