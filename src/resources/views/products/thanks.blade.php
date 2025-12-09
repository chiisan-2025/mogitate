{{-- resources/views/products/thanks.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>登録完了</title>

    <style>
        body {
            margin: 0;
            background: #f7f6f2;
            font-family: -apple-system, BlinkMacSystemFont, "Helvetica Neue", Arial, sans-serif;
        }

        .wrapper {
            max-width: 650px;
            margin: 80px auto;
            background: #fff;
            padding: 60px 40px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #444;
        }

        p {
            font-size: 16px;
            margin-bottom: 40px;
            color: #666;
        }

        .btn-home {
            display: inline-block;
            padding: 12px 32px;
            background: #f2a51a;
            color: #fff;
            text-decoration: none;
            border-radius: 999px;
            font-size: 15px;
            box-shadow: 0 6px 16px rgba(242,165,26,0.4);
        }

        .btn-home:hover {
            opacity: 0.85;
        }
    </style>
</head>

<body>
<div class="wrapper">

    <h1>登録が完了しました</h1>
    <p>商品の登録が正常に完了しました。<br>引き続き商品の管理を行うことができます。</p>

    <a href="{{ route('products.index') }}" class="btn-home">
        商品一覧へ戻る
    </a>

</div>
</body>
</html>