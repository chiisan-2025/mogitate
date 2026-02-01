<h1>商品一覧</h1>

<ul>
    @forelse ($items as $item)
        <li>
            <a href="{{ route('items.show', $item) }}">
                {{ $item->name }}（¥{{ number_format($item->price) }}）
            </a>
        </li>
    @empty
        <li>商品がありません</li>
    @endforelse
</ul>