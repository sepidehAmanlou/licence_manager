<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\LicenseRequestController;
use App\Http\Controllers\KeyboardSellerController;
use App\Http\Controllers\BankTestController;
use App\Http\Controllers\BankMockController;


Route::prefix('/users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'softDelete'])->name('softDelete');
    Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('restore');
    Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
});

Route::prefix('/licenses')->name('licenses.')->group(function () {
    Route::get('/', [LicenseController::class, 'index'])->name('index');
    Route::post('/', [LicenseController::class, 'store'])->name('store');
    Route::get('/{license}', [LicenseController::class, 'show'])->name('show');
    Route::put('/{license}', [LicenseController::class, 'update'])->name('update');
    Route::delete('/{license}', [LicenseController::class, 'softDelete'])->name('softDelete');
    Route::patch('/restore/{id}', [LicenseController::class, 'restore'])->name('restore');
    Route::delete('/destroy/{id}', [LicenseController::class, 'destroy'])->name('destroy');
});

Route::prefix('/license_requests')->name('license_requests.')->group(function () {
    Route::get('/', [LicenseRequestController::class, 'index'])->name('index');
    Route::post('/', [LicenseRequestController::class, 'store'])->name('store');
    Route::get('/{licenseRequest}', [LicenseRequestController::class, 'show'])->name('show');
    Route::put('/{licenseRequest}', [LicenseRequestController::class, 'update'])->name('update');
    Route::delete('/{licenseRequest}', [LicenseRequestController::class, 'softDelete'])->name('softDelete');
    Route::patch('/restore/{id}', [LicenseRequestController::class, 'restore'])->name('restore');
    Route::delete('/destroy/{id}', [LicenseRequestController::class, 'destroy'])->name('destroy');
    Route::post('/approved_requests', [LicenseRequestController::class, 'getApprovedRequests'])->name('getApprovedRequests');
});

Route::get('/keyboard_sellers', [KeyboardSellerController::class, 'getKeyboardSellersByCity'])->name('getKeyboardSellersByCity');

Route::get('/bank_test', [BankTestController::class, 'index'])->name('index');



Route::prefix('mock')->name('mock.')->group(function () {
    Route::get('/mellat_token', [BankMockController::class, 'mellatToken'])->name('mellatToken');
    Route::get('/mellat_transactions', [BankMockController::class, 'mellatTransactions'])->name('mellatTransactions');
    Route::get('/saman_token', [BankMockController::class, 'samanToken'])->name('samanToken');
    Route::get('/saman_transactions', [BankMockController::class, 'samanTransactions'])->name('samanTransactions');
});


