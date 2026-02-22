@extends('layouts.app')

@section('title', 'マイリスト')

@section('content')
  <h1>マイリスト</h1>

  {{-- ④ 未ログイン/0件なら何も表示（要件通り） --}}
  @if($items->isEmpty())
    {{-- 何も表示しない --}}
  @else
    <div class="grid">
      @foreach($items as $item)
        <a class="card" href="{{ route('items.show', $item) }}" style="display:block; width:200px; margin-bottom:20px;">
          <div class="thumb" style="position:relative;">
            <img
                src="{{ $item->image_url }}"
                alt="{{ $item->name }}"
                style="width:200px; height:200px; object-fit:cover;"
            >

            {{-- ③ Sold 表示 --}}
            @if($item->is_sold)
              <div style="
                position:absolute; top:8px; left:8px;
                background:#000; color:#fff;
                padding:4px 10px; font-size:12px;
              ">
                Sold
              </div>
            @endif
          </div>

          {{-- ② 商品名 --}}
          <div class="itemName" style="margin-top:8px; color:#000;">
            {{ $item->name }}
          </div>
        </a>
      @endforeach
    </div>
  @endif
@endsection