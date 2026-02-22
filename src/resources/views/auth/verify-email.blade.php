@extends('layouts.app')

@section('title', 'メール認証')

@section('content')
  <div style="max-width:520px; margin:40px auto;">
    <h1 style="font-size:20px; font-weight:bold; margin-bottom:12px;">
      メールアドレスの確認
    </h1>

    <p style="color:#555; margin-bottom:16px;">
      登録したメールアドレスに認証リンクを送信しました。<br>
      メール内のリンクをクリックして認証を完了してください。
    </p>

    @if (session('status') === 'verification-link-sent')
      <div style="color:green; font-weight:bold; margin-bottom:16px;">
        認証メールを再送しました。
      </div>
    @endif

    {{-- FN013: 再送 --}}
    <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom:16px;">
      @csrf
      <button type="submit" style="
        width:100%;
        height:44px;
        background:#ff4d4d;
        border:none;
        color:#fff;
        font-weight:bold;
        border-radius:4px;
        cursor:pointer;
      ">
        認証メールを再送する
      </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" style="background:none; border:none; color:#666; cursor:pointer;">
        ログアウト
      </button>
    </form>
  </div>
@endsection