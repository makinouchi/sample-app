<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SheetController;

Route::get('/payments', [PaymentController::class, 'index']);
Route::post('/payments', [PaymentController::class, 'store']);
Route::put('/payments/{id}', [PaymentController::class, 'update']);
Route::post('/payments/mark-processed', [PaymentController::class, 'markProcessed']);
Route::get('/sheets', [SheetController::class, 'apiIndex']);
