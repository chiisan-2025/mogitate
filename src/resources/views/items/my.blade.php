<h1>自分の出品商品</h1>
@if(session('success'))
    <div style="
        background:#e6fffa;
        border:1px solid #38b2ac;
        padding:10px;
        margin-bottom:15px;">
        {{ session('success') }}
    </div>
@endif

<ul>
@forelse ($items as $item)
    <li style="margin-bottom:12px;">
        @if($item->image_url)
            <img src="{{ $item->image_url }}" alt="商品画像"
                style="max-width:120px; height:auto; display:block; margin-bottom:6px;">
    @endif

    {{ $item->name }}（¥{{ number_format($item->price) }}）

    <a href="{{ route('items.edit', $item) }}">編集</a>

    <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('本当に削除しますか？')">削除</button>
    </form>

    @if($item->is_sold)
        <span style="color:red;">売り切れ</span>
    @endif
    </li>
@empty
    <li>出品した商品がありません</li>
@endforelse
</ul>