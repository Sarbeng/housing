<?php

use App\Http\Controllers\FeeAllocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalAgreementController;
use App\Http\Controllers\ServicePaymentController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tenants', TenantController::class);
    Route::resource('rentalAgreement', RentalAgreementController::class);
    Route::resource('units', UnitController::class);
    Route::resource('payments', ServicePaymentController::class);
    Route::resource('feesAllocation', FeeAllocationController::class);
    Route::patch('/feesAllocation/{id}/toggle-paid', [FeeAllocationController::class, 'togglePaid'])
    ->name('feesAllocation.togglePaid');
});

require __DIR__.'/auth.php';
