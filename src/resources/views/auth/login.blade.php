@extends('layouts.app')

@section('content')

<h1>ログイン</h1>

<form method="POST" action="{{ route('login') }}">
  @csrf

  <div>
    <label>メールアドレス</label>
    <input type="email" name="email" value="{{ old('email') }}">

    @error('email')
      <p style="color:red;">{{ $message }}</p>
    @enderror
  </div>

  <div>
    <label>パスワード</label>
    <input type="password" name="password">

    @error('password')
      <p style="color:red;">{{ $message }}</p>
    @enderror
  </div>

  {{-- 認証失敗のメッセージが email に付く想定（Fortify::username() が email のため） --}}
  {{-- もし auth.failed など別キーで出る場合に備えて、全体エラーも最後に1回だけ表示 --}}
  @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
    <p style="color:red;">{{ $errors->first() }}</p>
  @endif

  <button type="submit">ログイン</button>
</form>

<a href="{{ route('register') }}">会員登録へ</a>

@endsection