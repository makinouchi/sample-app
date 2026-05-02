<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('payment-form-b');
});

Route::get('/payments', function () {
    return view('payment-list-b');
});
