<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','フリマ')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<header style="background:black;color:white;padding:15px;">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:20px;">

    {{-- ロゴ --}}
    <a href="{{ route('items.index') }}" style="display:inline-block;">
  <img
    src="{{ asset('images/icons/logo.png') }}"
    alt="COACHTECH"
    style="height:28px;"
  >
</a>

    {{-- ✅ 検索フォーム（FN016） --}}
    <form method="GET" action="{{ route('items.index') }}" style="flex:1; max-width:520px;">
      {{-- tab を保持（おすすめ/マイリスト） --}}
      <input type="hidden" name="tab" value="{{ request('tab', 'recommend') }}">

      <input
        type="text"
        name="keyword"
        value="{{ request('keyword') }}"
        placeholder="なにをお探しですか？"
        style="width:100%; padding:10px 12px; border-radius:4px; border:none;"
      >
    </form>

    {{-- 右メニュー --}}
    <div style="display:flex; align-items:center; gap:15px;">
      @auth
        <a href="{{ route('profile.show') }}" style="color:white;text-decoration:none;">マイページ</a>
        <a href="{{ route('items.create') }}" style="color:white;text-decoration:none;">出品</a>

        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
          @csrf
          <button style="background:none;border:none;color:white;cursor:pointer;">
            ログアウト
          </button>
        </form>
      @else
        <a href="{{ route('login') }}" style="color:white;text-decoration:none;">ログイン</a>
      @endauth
    </div>

  </div>
</header>

<main style="padding:30px;">
  @yield('content')
</main>

</body>
</html>