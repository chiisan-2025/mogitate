<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Handle the response after registration.
     */
    public function toResponse($request)
    {
        // Fortifyは登録直後に自動ログインするので、
        // 要件（ログイン画面へ遷移）に合わせてログアウトする
        Auth::logout();

        // ログイン画面へリダイレクト
        return redirect()->route('login');
    }
}