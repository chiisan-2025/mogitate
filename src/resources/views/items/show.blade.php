<h1>{{ $item->name }}</h1>

<p>¥{{ $item->price }}</p>
<p>{{ $item->description }}</p>

<p>カテゴリ：</p>

<ul>
  @forelse($item->categories as $category)
    <li>{{ $category->name }}</li>
  @empty
    <li>未分類</li>
  @endforelse
</ul>

<a href="{{ route('items.index') }}">一覧に戻る</a>

@if(!$item->is_sold)
  <form action="{{ route('orders.store', $item) }}" method="POST">
    @csrf
    <button type="submit">購入する</button>
  </form>
@else
  <p>売り切れ</p>
@endif