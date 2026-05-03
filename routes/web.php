<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SheetController;

Route::get('/', function () {
    return view('payment-form-b');
});

Route::get('/payments', function () {
    return view('payment-list-b');
});

Route::get('/sheets', [SheetController::class, 'index']);
