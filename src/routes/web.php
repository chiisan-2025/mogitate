<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;

// 一覧
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

// 検索・並び替え
Route::get('/products/search', [ProductController::class, 'search'])
    ->name('products.search');
Route::get('/products/sort', [ProductController::class, 'sort'])
    ->name('products.sort');

// 詳細
Route::get('/products/detail/{id}', [ProductController::class, 'show'])
    ->name('products.show');

// 新規登録（表示・保存）
Route::get('/products/register', [ProductController::class, 'create'])
    ->name('products.create');
Route::post('/products/register', [ProductController::class, 'store'])
    ->name('products.store');

// 編集・更新
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])
    ->name('products.edit');

Route::post('/products/{id}/edit', [ProductController::class, 'update'])
    ->name('products.update');

// 削除
Route::post('/products/{id}/delete', [ProductController::class, 'destroy'])
    ->name('products.delete');

Route::get('/products/thanks', function () {
    return view('products.thanks');
})->name('products.thanks');
