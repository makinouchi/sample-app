<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SheetController;
use App\Http\Controllers\CommandController;

Route::get('/', function () {
    return view('payment-form-b');
});

Route::get('/payments', function () {
    return view('payment-list-b');
});

Route::get('/sheets', [SheetController::class, 'index']);

// Artisanコマンド実行画面
Route::get('/commands', [CommandController::class, 'index'])->name('command.index');
Route::post('/commands/run', [CommandController::class, 'run'])->name('command.run');

