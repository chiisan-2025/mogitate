<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyListController;

/*
|--------------------------------------------------------------------------
| 公開ページ（未ログインOK）
|--------------------------------------------------------------------------
*/
Route::get('/', [ItemController::class, 'index'])->name('items.index');

Route::get('/items/{item}', [ItemController::class, 'show'])
    ->where(['item' => '[0-9]+'])
    ->name('items.show');


/*
|--------------------------------------------------------------------------
| 要ログイン（未認証でもOK）
|--------------------------------------------------------------------------
| FN015: 未認証の場合は何も表示されない → MyListControllerで空表示にする
| FN012/FN013: メール認証・再送は未認証ユーザーが使うので verified を付けない
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ✅ マイリスト（未認証は controller 側で空表示）
    Route::get('/mylist', [MyListController::class, 'index'])->name('mylist.index');

    // ✅ プロフィール編集は未認証でもOK（要件に合わせて）
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ✅ メール認証案内ページ（未認証ユーザーが見る）
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // ✅ メール内リンクを踏んだ時（認証完了）
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('profile.edit')->with('success', 'メール認証が完了しました');
    })->middleware('signed')->name('verification.verify');

    // ✅ FN013: 認証メール再送
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        // Blade が session('status') === 'verification-link-sent' を見ているのでこれが正解
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');
});


/*
|--------------------------------------------------------------------------
| 要ログイン + メール認証済み（verified 必須）
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // ✅ プロフィール表示（購入/出品タブ含む）
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    /*
    |--------------------------------------------------------------------------
    | items（出品・編集など）
    |--------------------------------------------------------------------------
    */
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');

    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');

    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    Route::get('/my-items', [ItemController::class, 'myItems'])->name('items.my');

    /*
    |--------------------------------------------------------------------------
    | 購入
    |--------------------------------------------------------------------------
    */
    Route::get('/items/{item}/purchase', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/items/{item}/purchase', [OrderController::class, 'store'])->name('orders.store');

    /*
    |--------------------------------------------------------------------------
    | お気に入り
    |--------------------------------------------------------------------------
    */
    Route::post('/items/{item}/favorite', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/items/{item}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    /*
    |--------------------------------------------------------------------------
    | コメント
    |--------------------------------------------------------------------------
    */
    Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});