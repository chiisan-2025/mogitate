<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // ログイン後はプロフィール編集へ
        return redirect()->route('profile.edit');
        // もしrouteが無ければ → return redirect('/profile/edit');
    }
}