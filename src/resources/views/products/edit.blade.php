{{-- resources/views/products/edit.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品編集 | mogitate</title>

    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Helvetica Neue",
                         "Yu Gothic", "游ゴシック体", "YuGothic", "メイリオ", sans-serif;
            background: #f7f7f7;
            color: #555;
        }

        /* 上のロゴ部分 */
        .site-header {
            padding: 16px 40px;
            background: #fff7d9;
        }
        .site-logo {
            font-family: "Times New Roman", serif;
            font-size: 26px;
            font-weight: bold;
            color: #f3a000;
        }

        /* 白いカード部分 */
        .page-wrapper {
            max-width: 1000px;
            margin: 40px auto 60px;
            background: #ffffff;
            padding: 40px 60px 50px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            box-sizing: border-box;
        }

        .breadcrumb {
            font-size: 13px;
            margin-bottom: 20px;
        }
        .breadcrumb a {
            color: #4080ff;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .page-title {
            font-size: 24px;
            margin: 0 0 28px;
        }

        /* フォームレイアウト（画像左 / 項目右） */
        .form-layout {
            display: flex;
            gap: 60px;
            align-items: flex-start;
            margin-bottom: 32px;
        }
        .form-column {
            box-sizing: border-box;
        }
        .image-column {
            width: 320px;
        }
        .info-column {
            flex: 1;
        }

        .field-label {
            font-size: 14px;
            font-weight: 600;
            color: #555;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }
        .badge-required {
            display: inline-block;
            font-size: 11px;
            color: #fff;
            background: #ff5f5f;
            padding: 2px 6px;
            border-radius: 2px;
        }
        .field-sub {
            font-size: 11px;
            color: #999;
            margin-left: 8px;
        }

        .current-image {
            margin: 8px 0 12px;
        }
        .current-image img {
            width: 320px;
            height: auto;
            border-radius: 4px;
            object-fit: cover;
        }

        .file-input-wrap input[type="file"] {
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 14px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 140px;
        }

        .field-note {
            font-size: 12px;
            color: #999;
            margin-top: 4px;
        }

        .season-options {
            display: flex;
            gap: 18px;
            margin-top: 6px;
        }
        .season-options label {
            font-size: 14px;
        }

        /* 説明だけ幅いっぱい */
        .description-group {
            margin-bottom: 32px;
        }

        /* エラー表示 */
        .error-text {
            color: #e54646;
            font-size: 12px;
            margin-top: 4px;
        }

        /* ボタン周り */
        .form-footer {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 24px;
            margin-top: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 140px;
            padding: 10px 24px;
            font-size: 14px;
            border-radius: 3px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            box-sizing: border-box;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #555;
        }
        .btn-secondary:hover {
            background: #f6bdbdff;
        }

        .btn-primary {
            background: #ffc824;
            color: #4b3a00;
        }
        .btn-primary:hover {
            background: #f0b917;
        }

        .delete-form {
            margin-top: 12px;
            text-align: right;
        }
        .btn-delete {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #555;
            font-size: 18px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-delete:hover {
            background: #d5d5d5;
        }
        .btn-delete svg {
            width: 20px;
            height: 20px;
        }

        @media (max-width: 800px) {
            .page-wrapper {
                padding: 24px 16px 32px;
            }
            .form-layout {
                flex-direction: column;
                gap: 24px;
            }
            .image-column {
                width: 100%;
            }
            .current-image img {
                width: 100%;
                max-width: 360px;
            }
            .form-footer {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="site-logo">mogitate</div>
</header>

<main class="page-wrapper">
    <div class="breadcrumb">
        <a href="{{ route('products.index') }}">商品一覧</a> ＞ {{ $product->name }}
    </div>

    <h1 class="page-title">商品編集</h1>

    <form id="update-form"
          action="{{ route('products.update', $product->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="form-layout">
            {{-- 左：画像 --}}
            <div class="form-column image-column">
                <p class="field-label">
                    商品画像 <span class="badge-required">必須</span>
                </p>
                <div class="current-image">
                    <img src="{{ asset('storage/fruits-img/' . $product->image) }}"
                         alt="{{ $product->name }}">
                </div>
                <div class="file-input-wrap">
                    <input type="file" name="image">
                </div>
                @error('image')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            {{-- 右：基本項目 --}}
            <div class="form-column info-column">
                <div class="form-group">
                    <label for="name" class="field-label">
                        商品名 <span class="badge-required">必須</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $product->name) }}">
                    @error('name')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price" class="field-label">
                        値段 <span class="badge-required">必須</span>
                    </label>
                    <input type="number"
                           id="price"
                           name="price"
                           value="{{ old('price', $product->price) }}">
                    <p class="field-note">0〜10000円以内で入力してください</p>
                    @error('price')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <span class="field-label">
                        季節 <span class="badge-required">必須</span>
                        <span class="field-sub">※ 複数選択可</span>
                    </span>
                    <div class="season-options">
                        @php
                            $selectedSeasons = old('season_ids', $product->seasons->pluck('id')->toArray());
                        @endphp
                        @foreach ($seasons as $season)
                            <label>
                                <input type="checkbox"
                                       name="season_ids[]"
                                       value="{{ $season->id }}"
                                       {{ in_array($season->id, $selectedSeasons) ? 'checked' : '' }}>
                                {{ $season->name }}
                            </label>
                        @endforeach
                    </div>
                    @error('season_ids')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- 商品説明 --}}
        <div class="description-group">
            <label for="description" class="field-label">
                商品説明 <span class="badge-required">必須</span>
            </label>
            <textarea id="description"
                      name="description"
                      rows="5">{{ old('description', $product->description) }}</textarea>
            <p class="field-note">120文字以内で入力してください</p>
            @error('description')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        {{-- 戻る / 保存ボタン --}}
        <div class="form-footer">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
            <button type="submit" class="btn btn-primary">変更を保存</button>
        </div>
    </form>

    {{-- 削除ボタン（別フォーム） --}}
    <form action="{{ route('products.delete', $product->id) }}"
          method="POST"
          class="delete-form"
          onsubmit="return confirm('削除してもよろしいですか？');">
        @csrf
        <button type="submit" class="btn btn-delete">🗑</button>
    </form>
</main>
</body>
</html>