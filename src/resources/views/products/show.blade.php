
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品詳細</title>
</head>
<body>
    <h1>{{ $product->name }} の詳細</h1>

    <div style="border:1px solid #ccc; padding:10px; margin:10px;">
        <img src="{{ asset('storage/fruits-img/' . $product->image) }}"
            alt="{{ $product->name }}"
            style="width:160px;">

        <p>商品名：{{ $product->name }}</p>
        <p>価格：¥{{ number_format($product->price) }}</p>

        <p>季節：
            {{ $product->seasons->pluck('name')->join(' / ') }}
        </p>

        <p>説明：</p>
        <p>{{ $product->description }}</p>
    </div>

    <p>
        <a href="{{ route('products.index') }}">← 商品一覧に戻る</a>
    </p>
</body>
</html>