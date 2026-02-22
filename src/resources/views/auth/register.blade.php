<h1>会員登録</h1>

@if ($errors->any())
  <ul>
    @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
  </ul>
@endif

<form method="POST" action="{{ route('register') }}">
  @csrf

  <div>
    <label>ユーザー名</label>
    <input type="text" name="name" value="{{ old('name') }}">
  </div>

  <div>
    <label>メールアドレス</label>
    <input type="text" name="email" value="{{ old('email') }}">
  </div>

  <div>
    <label>パスワード</label>
    <input type="password" name="password">
  </div>

  <div>
    <label>確認用パスワード</label>
    <input type="password" name="password_confirmation">
  </div>

  <button type="submit">登録</button>
</form>

<a href="{{ route('login') }}">ログインへ</a>