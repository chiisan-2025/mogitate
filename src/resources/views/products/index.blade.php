{{-- resources/views/products/index.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>

    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Helvetica Neue", Arial, "YuGothic", "游ゴシック体", "Yu Gothic", "游ゴシック Medium", "Yu Gothic Medium", sans-serif;
            background: #f7f6f2;
        }

        .page-wrapper {
            max-width: 1200px;
            margin: 40px auto;
            padding: 40px 40px 60px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        }

        /* ヘッダー */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #f2a51a;
        }

        .btn-add {
            display: inline-block;
            padding: 10px 24px;
            font-size: 14px;
            border-radius: 999px;
            background: #f2a51a;
            color: #fff;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(242, 165, 26, 0.4);
        }

        .btn-add:hover {
            opacity: 0.9;
        }

        .layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 32px;
        }

        /* サイドバー（検索・並び替え） */
        .sidebar-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .filter-box {
            margin-bottom: 32px;
        }

        .filter-label {
            font-size: 13px;
            margin-bottom: 8px;
            display: block;
        }

        .input-text,
        .select-box {
            width: 100%;
            padding: 10px 12px;
            border-radius: 999px;
            border: 1px solid #ddd;
            font-size: 13px;
            box-sizing: border-box;
        }

        .btn-search {
            margin-top: 10px;
            width: 100%;
            padding: 10px 0;
            border-radius: 999px;
            border: none;
            background: #f2a51a;
            color: #fff;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-search:hover {
            opacity: 0.9;
        }

        /* 商品グリッド */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        .product-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .product-image-wrap {
            width: 100%;
            height: 190px;
            overflow: hidden;
        }

        .product-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .product-body {
            padding: 16px 18px 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 14px;
        }

        .product-name {
            font-weight: 700;
        }

        .product-price {
            font-weight: 700;
        }

        .product-meta {
            font-size: 12px;
            color: #777;
        }

        .card-footer {
            padding: 10px 18px 16px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            font-size: 12px;
        }

        .card-link {
            color: #888;
            text-decoration: none;
        }

        .card-link:hover {
            text-decoration: underline;
        }

        .card-link--danger {
            color: #c44;
        }

                /* ページネーション（Laravelの links() だけを装飾） */
        .pagination-wrapper {
            margin-top: 32px;
            text-align: center;
            font-size: 13px;
        }

        .pagination-wrapper nav {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        /* ←★ ここを追加：ページネーション内の svg アイコンを消す */
        .pagination-wrapper svg {
            display: none;
        }
        .product-card-link {
        text-decoration: none;   /* 下線なし */
        color: inherit;          /* 文字色そのまま */
        display: block;          /* カードサイズに合わせる */
    }

    /* hover 時のちょっとした演出（お好みで） */
    .product-card-link:hover .product-card {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
        transition: box-shadow 0.15s ease, transform 0.15s ease;
    }
    </style>
</head>
<body>
<div class="page-wrapper">

    {{-- ヘッダー --}}
    <div class="header">
        <div class="logo">mogitate</div>
        <a href="{{ route('products.create') }}" class="btn-add">＋ 商品を追加</a>
    </div>

    <div class="layout">
        {{-- 左側：検索＆並び替え --}}
        <aside>
            <div class="sidebar-title">商品一覧</div>

            {{-- 検索 --}}
            <div class="filter-box">
                <form action="{{ route('products.search') }}" method="GET">
                    <label class="filter-label">商品名で検索</label>
                    <input
                        type="text"
                        name="keyword"
                        class="input-text"
                        value="{{ $keyword ?? '' }}"
                        placeholder="商品名・説明で検索"
                    >
                    <button type="submit" class="btn-search">検索</button>
                </form>
            </div>

            {{-- 並び替え --}}
            <div class="filter-box">
                <form action="{{ route('products.sort') }}" method="GET">
                    <label class="filter-label">価格順で表示</label>
                    <select name="sort" class="select-box" onchange="this.form.submit()">
                        <option value="">価格で並べ替え</option>
                        <option value="price_asc"  {{ (isset($sort) && $sort === 'price_asc')  ? 'selected' : '' }}>価格が安い順</option>
                        <option value="price_desc" {{ (isset($sort) && $sort === 'price_desc') ? 'selected' : '' }}>価格が高い順</option>
                    </select>
                    <noscript>
                        <button type="submit" class="btn-search">並び替え</button>
                    </noscript>
                </form>
            </div>
        </aside>

        {{-- 右側：商品カード --}}
        <main>
            @if ($products->isEmpty())
                <p>該当する商品がありませんでした。</p>
            @else
                <div class="products-grid">
                    @foreach ($products as $product)
                        <a href="{{ route('products.show', $product->id) }}" class="product-card-link">
                            <div class="product-card">
                                <div class="product-image-wrap">
                                    <img src="{{ asset('storage/fruits-img/' . $product->image) }}"
                                    alt="{{ $product->name }}">
                                </div>
                                <div class="product-body">
                                    <div class="product-name">{{ $product->name }}</div>
                                    <div class="product-price">¥{{ number_format($product->price) }}</div>
                                    <div class="product-meta">
                                        季節：{{ $product->seasons->pluck('name')->join(' / ') }}
                                    </div>
                                </div>
                            </div>
                    @endforeach
                </div>
            @endif

            <div class="pagination-wrapper">
                {{ $products->appends(request()->query())->links() }}
            </div>

        </main>
    </div>
</div>
</body>
</html>