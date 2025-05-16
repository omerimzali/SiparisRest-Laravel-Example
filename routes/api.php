<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/', [OrderController::class, 'index']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
    Route::post('/{order}/calculate-discount', [OrderController::class, 'calculateDiscount']);
});
