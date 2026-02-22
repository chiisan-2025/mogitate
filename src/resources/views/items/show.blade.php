@extends('layouts.app')

@section('title', $item->name)

@section('content')

{{-- フラッシュ --}}
@if(session('error'))
  <p style="color:#c00; font-weight:bold; margin-bottom:12px;">{{ session('error') }}</p>
@endif
@if(session('success'))
  <p style="color:#0a0; font-weight:bold; margin-bottom:12px;">{{ session('success') }}</p>
@endif

<div style="display:flex; gap:40px; align-items:flex-start; max-width:1100px; margin:0 auto;">

  {{-- 左：画像 --}}
  <div style="flex:1; max-width:520px;">
    @if($item->image_url)
      <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
           style="width:100%; height:auto; display:block; border:1px solid #eee;">
    @endif
  </div>

  {{-- 右：情報 --}}
  <div style="flex:1; min-width:360px;">

    <h1 style="font-size:28px; margin:0 0 8px;">{{ $item->name }}</h1>
    <p style="margin:0 0 10px; color:#666;">ブランド名</p>

    <p style="font-size:22px; font-weight:bold; margin:0 0 10px;">
      ¥{{ number_format($item->price) }} <span style="font-size:14px; font-weight:normal;">(税込)</span>
    </p>

    {{-- アイコン列（♥ と 💬） --}}
    <div style="display:flex; gap:18px; align-items:flex-end; margin:10px 0 16px;">

      {{-- ♥（押したらPOST/DELETE、下に数字） --}}
      <div style="text-align:center;">
        @auth
          @php $isFavorited = $item->isFavoritedBy(auth()->user()); @endphp

          @if($isFavorited)
            <form method="POST" action="{{ route('favorites.destroy', $item) }}" style="margin:0;">
              @csrf
              @method('DELETE')
              <button type="submit" style="background:transparent; border:0; cursor:pointer; padding:0;">
                <img src="{{ asset('images/icons/heart-active.png') }}" width="22" alt="liked">
              </button>
            </form>
          @else
            <form method="POST" action="{{ route('favorites.store', $item) }}" style="margin:0;">
              @csrf
              <button type="submit" style="background:transparent; border:0; cursor:pointer; padding:0;">
                <img src="{{ asset('images/icons/heart-default.png') }}" width="22" alt="like">
              </button>
            </form>
          @endif
        @else
          <img src="{{ asset('images/icons/heart-default.png') }}" width="22" alt="like">
        @endauth

        <div style="font-size:12px; margin-top:4px;">{{ $item->favorites_count }}</div>
      </div>

      {{-- 💬 --}}
      <div style="text-align:center;">
        <img src="{{ asset('images/icons/comment.png') }}" width="22" alt="comment">
        <div style="font-size:12px; margin-top:4px;">{{ $item->comments_count }}</div>
      </div>
    </div>

    {{-- 購入ボタン（見本の赤帯） --}}
    <div style="margin:0 0 18px;">
      @auth
        @if($item->is_sold)
          <div style="background:#111; color:#fff; padding:14px; text-align:center; font-weight:bold;">
            SOLD
          </div>
        @elseif(auth()->id() === $item->user_id)
          <div style="color:#999; font-weight:bold;">自分が出品した商品です</div>
        @else
          <a href="{{ route('orders.create', $item) }}"
             style="display:block; background:#e66; color:#fff; text-align:center;
                    padding:14px 12px; font-weight:bold; text-decoration:none;">
            購入手続きへ
          </a>
        @endif
      @endauth

      @guest
        @if($item->is_sold)
          <div style="background:#111; color:#fff; padding:14px; text-align:center; font-weight:bold;">
            SOLD
          </div>
        @else
          <a href="{{ route('login') }}"
             style="display:block; background:#e66; color:#fff; text-align:center;
                    padding:14px 12px; font-weight:bold; text-decoration:none;">
            ログインして購入する
          </a>
        @endif
      @endguest
    </div>

    {{-- 商品説明 --}}
    <h2 style="margin:22px 0 8px; font-size:18px;">商品説明</h2>
    <p style="margin:0 0 18px; line-height:1.8;">{{ $item->description }}</p>

    {{-- 商品情報（見本の表っぽい） --}}
    <h2 style="margin:22px 0 10px; font-size:18px;">商品の情報</h2>
    <div style="display:grid; grid-template-columns:160px 1fr; row-gap:10px; column-gap:16px;">

      <div style="font-weight:bold;">カテゴリ</div>
      <div>
        @forelse($item->categories as $category)
          <span style="display:inline-block; background:#eee; border-radius:14px; padding:4px 10px; margin-right:6px; font-size:12px;">
            {{ $category->name }}
          </span>
        @empty
          <span style="color:#999;">未分類</span>
        @endforelse
      </div>

      <div style="font-weight:bold;">商品の状態</div>
      <div>良好</div>
    </div>

    <div style="margin-top:14px;">
      <a href="{{ route('items.index') }}" style="color:#333;">一覧に戻る</a>
    </div>

  </div>
</div>

<hr style="margin:40px 0;">

{{-- コメント --}}
<div style="max-width:900px; margin:0 auto;">
  <h2 style="font-size:22px; margin:0 0 10px;">コメント（{{ $item->comments_count }}）</h2>

  @forelse($item->comments as $comment)
    <div style="display:flex; gap:14px; padding:14px 0; border-bottom:1px solid #eee;">

      {{-- アイコン丸（仮） --}}
      <div style="width:44px; height:44px; border-radius:50%; background:#ddd;"></div>

      <div style="flex:1;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <strong>{{ $comment->user->name }}</strong>

          {{-- Cでここを整える（削除） --}}
          @auth
            @if($comment->user_id === auth()->id())
              <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit"
                        style="background:transparent; border:0; color:#999; cursor:pointer; font-size:12px;"
                        onclick="return confirm('このコメントを削除しますか？')">
                  削除
                </button>
              </form>
            @endif
          @endauth
        </div>

        <div style="margin-top:6px; background:#f2f2f2; padding:10px 12px; border-radius:6px;">
          {{ $comment->comment }}
        </div>
      </div>
    </div>
  @empty
    <p style="color:#666;">まだコメントはありません</p>
  @endforelse

  <h3 style="margin:26px 0 10px; font-size:18px;">商品へのコメント</h3>

  @auth
    <form action="{{ route('comments.store', $item) }}" method="POST">
      @csrf
      <textarea name="comment" rows="6" style="width:100%; padding:10px;" required>{{ old('comment') }}</textarea>
      @error('comment')
        <div style="color:#c00;">{{ $message }}</div>
      @enderror
      <button type="submit"
              style="margin-top:10px; width:100%; background:#e66; color:#fff; padding:12px; border:0; cursor:pointer; font-weight:bold;">
        コメントを送信する
      </button>
    </form>
  @else
    <p><a href="{{ route('login') }}">ログインしてコメントする</a></p>
  @endauth
</div>

@endsection