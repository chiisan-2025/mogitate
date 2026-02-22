{{-- resources/views/items/index.blade.php --}}
@extends('layouts.app')

@section('title', '商品一覧')

@section('content')

@php
  $isRecommend = ($tab === 'recommend');
  $keyword = request('keyword');
  $showItems = $isRecommend ? $items : $mylistItems;
@endphp

{{-- タブ --}}
<div style="border-bottom:1px solid #ccc; margin-bottom:20px;">
  <a href="{{ route('items.index', ['tab' => 'recommend', 'keyword' => $keyword]) }}"
     style="margin-right:30px; padding:10px 0; display:inline-block; text-decoration:none; {{ $isRecommend ? 'color:#ff4d4d; font-weight:bold;' : 'color:#666;' }}">
    おすすめ
  </a>

  <a href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => $keyword]) }}"
     style="padding:10px 0; display:inline-block; text-decoration:none; {{ !$isRecommend ? 'color:#ff4d4d; font-weight:bold;' : 'color:#666;' }}">
    マイリスト
  </a>
</div>

@if($showItems->isEmpty())
  {{-- 何も表示しない（要件通り） --}}
@else
  <div style="display:flex; gap:30px; flex-wrap:wrap;">
    @foreach($showItems as $item)
      <div style="width:220px;">

        {{-- ✅ SOLD のときはリンクを作らない --}}
        @if($item->is_sold)
          <div style="text-decoration:none; color:#000;">
            <div style="position:relative; width:220px; height:220px; background:#eee;">
              <img
                src="{{ $item->image_url }}"
                alt="{{ $item->name }}"
                style="width:220px; height:220px; object-fit:cover;"
              >
              <div style="position:absolute; top:10px; left:10px; background:#000; color:#fff; padding:4px 10px; font-size:12px;">
                SOLD
              </div>
            </div>
          </div>
        @else
          {{-- ✅ 販売中だけ詳細へ --}}
          <a href="{{ route('items.show', $item) }}" style="text-decoration:none; color:#000; display:block;">
            <div style="position:relative; width:220px; height:220px; background:#eee;">
              <img
                src="{{ $item->image_url }}"
                alt="{{ $item->name }}"
                style="width:220px; height:220px; object-fit:cover;"
              >
            </div>
          </a>
        @endif

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:8px;">
          <div>{{ $item->name }}</div>

          {{-- いいねボタン（ログイン時だけ表示） --}}
          @auth
            @php $isFavorited = $item->isFavoritedBy(auth()->user()); @endphp

            @if($isFavorited)
              <form method="POST" action="{{ route('favorites.destroy', $item) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" style="background:none; border:none; cursor:pointer; padding:0;">
                  <img src="{{ asset('images/icons/heart-active.png') }}" alt="unfavorite" width="22">
                </button>
              </form>
            @else
              <form method="POST" action="{{ route('favorites.store', $item) }}" style="display:inline;">
                @csrf
                <button type="submit" style="background:none; border:none; cursor:pointer; padding:0;">
                  <img src="{{ asset('images/icons/heart-default.png') }}" alt="favorite" width="22">
                </button>
              </form>
            @endif
          @endauth
        </div>

      </div>
    @endforeach
  </div>
@endif

@endsection