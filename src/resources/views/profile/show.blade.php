@extends('layouts.app')

@section('title', 'プロフィール')

@php
  // ?tab=sell / ?tab=buy で切り替え（デフォ sell）
  $tab = request('tab', 'sell');
@endphp

@section('content')
<div class="profileWrap">

  <section class="profileTop">
    <div class="profileLeft">
      <div class="avatar">
        @if(!empty($profile) && !empty($profile->icon_path))
          <img src="{{ asset($profile->icon_path) }}" alt="アイコン">
        @endif
      </div>

      <div class="profileName">
        {{ auth()->user()->name }}
      </div>
    </div>

    <a class="editBtn" href="{{ route('profile.edit') }}">プロフィールを編集</a>
  </section>

  <nav class="tabs">
    <a class="tabLink {{ $tab==='sell' ? 'is-active' : '' }}" href="{{ route('profile.show', ['tab' => 'sell']) }}">
      出品した商品
    </a>
    <a class="tabLink {{ $tab==='buy' ? 'is-active' : '' }}" href="{{ route('profile.show', ['tab' => 'buy']) }}">
      購入した商品
    </a>
  </nav>

  @if($tab === 'sell')
    <div class="grid">
      @forelse($sellItems as $item)
        <a class="card" href="{{ route('items.show', $item) }}">
          <div class="thumb">
            <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->name }}">
          </div>
          <div class="itemName">{{ $item->name }}</div>
        </a>
      @empty
        <p>出品した商品はありません</p>
      @endforelse
    </div>
  @else
    <div class="grid">
      @forelse($buyItems as $item)
        <a class="card" href="{{ route('items.show', $item) }}">
          <div class="thumb">
            <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->name }}">
          </div>
          <div class="itemName">{{ $item->name }}</div>
        </a>
      @empty
        <p>購入した商品はありません</p>
      @endforelse
    </div>
  @endif

</div>
@endsection
