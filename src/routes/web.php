<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;

Route::get('/', [ItemController::class, 'index'])
    ->name('items.index');

Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

Route::post('/items/{item}/purchase', [OrderController::class, 'store'])
    ->middleware('auth')
    ->name('orders.store');
