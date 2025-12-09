<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品登録</title>

    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Helvetica Neue", Arial, "游ゴシック体", "YuGothic", "メイリオ", sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 40px 0;
        }

        .page-header {
            max-width: 1040px;
            margin: 0 auto 16px;
            color: #f4b739; /* mogitateロゴっぽい色 */
            font-size: 28px;
            font-weight: 600;
        }

        .page-container {
            max-width: 1040px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px 60px 60px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            box-sizing: border-box;
        }

        .breadcrumb {
            font-size: 13px;
            margin-bottom: 24px;
            color: #777;
        }

        .form-row {
            display: flex;
            gap: 60px;
        }

        .form-column {
            flex: 1;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            margin-bottom: 8px;
            color: #555;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 10px 12px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        .form-textarea {
            min-height: 140px;
            resize: vertical;
        }

        .season-list label {
            margin-right: 20px;
            font-size: 14px;
        }

        .error-text {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 4px;
            line-height: 1.4;
        }

        .button-area {
            margin-top: 40px;
            text-align: center;
        }

        .btn {
            display: inline-block;
            min-width: 160px;
            padding: 12px 24px;
            font-size: 14px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #333;
            margin-right: 16px;
        }

        .btn-primary {
            background: #f4b739;
            color: #fff;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="page-header">mogitate</div>

    <div class="page-container">
        <div class="breadcrumb">
            <a href="{{ route('products.index') }}">商品一覧</a> ＞ 商品登録
        </div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                {{-- 左カラム：画像 --}}
                <div class="form-column">
                    <div class="form-group">
                        <label class="form-label">商品画像</label>
                        <input type="file" name="image" class="form-input">
                        @if ($errors->has('image'))
                            @foreach ($errors->get('image') as $msg)
                                <p class="error-text">・{{ $msg }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- 右カラム：商品名・値段・季節 --}}
                <div class="form-column">
                    <div class="form-group">
                        <label class="form-label">商品名</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-input"
                            placeholder="商品名を入力">
                            @if ($errors->has('name'))
                                @foreach ($errors->get('name') as $msg)
                                    <p class="error-text">・{{ $msg }}</p>
                                @endforeach
                            @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">値段</label>
                        <input
                            type="text"
                            name="price"
                            value="{{ old('price') }}"
                            class="form-input"
                            placeholder="値段を入力">
                            @if ($errors->has('price'))
                                @foreach ($errors->get('price') as $msg)
                                    <p class="error-text">・{{ $msg }}</p>
                                @endforeach
                            @endif
                    </div>

                    <div class="form-group">
                        <span class="form-label">季節</span>
                        <div class="season-list">
                            @foreach ($seasons as $season)
                                <label>
                                    <input
                                        type="checkbox"
                                        name="season_ids[]"
                                        value="{{ $season->id }}"
                                        {{ in_array($season->id, old('season_ids', [])) ? 'checked' : '' }}
                                    >
                                    {{ $season->name }}
                                </label>
                            @endforeach
                        </div>
                        @if ($errors->has('season_ids'))
                            @foreach ($errors->get('season_ids') as $msg)
                                <p class="error-text">・{{ $msg }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- 商品説明（横いっぱい） --}}
            <div class="form-group">
                <label class="form-label">商品説明</label>
                <textarea
                    name="description"
                    class="form-textarea"
                    placeholder="商品の説明を入力">{{ old('description') }}</textarea>
                @if ($errors->has('description'))
                    @foreach ($errors->get('description') as $msg)
                        <p class="error-text">・{{ $msg }}</p>
                    @endforeach
                @endif
            </div>

            <div class="button-area">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
                <button type="submit" class="btn btn-primary">登録を保存</button>
            </div>
        </form>
    </div>
</body>
