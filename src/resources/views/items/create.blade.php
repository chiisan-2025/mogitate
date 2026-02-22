{{-- resources/views/items/create.blade.php --}}
@extends('layouts.app')

@section('title', '商品の出品')

@section('content')
<div style="max-width:700px; margin:0 auto;">

  <h1 style="text-align:center; font-size:24px; margin:30px 0 40px;">
    商品の出品
  </h1>

  {{-- バリデーションエラー --}}
  @if ($errors->any())
    <div style="background:#ffecec; border:1px solid #ffb3b3; padding:12px; margin-bottom:20px;">
      <ul style="margin:0; padding-left:18px;">
        @foreach ($errors->all() as $error)
          <li style="color:#d00;">{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- 商品画像 --}}
    <div style="margin-bottom:28px;">
      <div style="font-weight:bold; margin-bottom:10px;">商品画像</div>

      <div style="
        border:1px dashed #ccc;
        padding:18px;
        border-radius:4px;
      ">

        <div style="display:flex; align-items:center; gap:14px;">
          <label style="
            display:inline-block;
            padding:8px 14px;
            border:1px solid #ff4d4d;
            color:#ff4d4d;
            border-radius:6px;
            cursor:pointer;
            font-weight:bold;
            background:#fff;
            white-space:nowrap;
          ">
            画像を選択する
            <input
              id="imageInput"
              type="file"
              name="image"
              accept="image/*"
              style="display:none;"
            >
          </label>

          {{-- ✅ 選択状態がわかるテキスト --}}
          <div id="fileName" style="color:#666; font-size:14px;">
            選択されていません
          </div>
        </div>

        {{-- ✅ 画像プレビュー --}}
        <div style="margin-top:14px;">
          <img
            id="imagePreview"
            src=""
            alt="プレビュー"
            style="display:none; width:220px; height:220px; object-fit:cover; border:1px solid #eee; border-radius:4px;"
          >
        </div>

      </div>

      @error('image')
        <div style="color:#ff4d4d; margin-top:8px;">{{ $message }}</div>
      @enderror
    </div>

    {{-- 商品の詳細 --}}
    <div style="font-weight:bold; font-size:18px; margin:20px 0 10px;">
      商品の詳細
    </div>
    <div style="border-top:1px solid #ccc; margin-bottom:20px;"></div>

    {{-- カテゴリー --}}
    <div style="margin-bottom:24px;">
      <div style="font-weight:bold; margin-bottom:10px;">カテゴリー</div>

      <div style="display:flex; flex-wrap:wrap; gap:10px;">
        @foreach($categories as $category)
          <label style="
            display:inline-flex;
            align-items:center;
            gap:6px;
            border:1px solid #ff4d4d;
            color:#ff4d4d;
            border-radius:16px;
            padding:6px 12px;
            cursor:pointer;
            font-size:12px;
          ">
            <input
              type="checkbox"
              name="categories[]"
              value="{{ $category->id }}"
              {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
              style="accent-color:#ff4d4d;"
            >
            {{ $category->name }}
          </label>
        @endforeach
      </div>

      @error('categories')
        <div style="color:#ff4d4d; margin-top:8px;">{{ $message }}</div>
      @enderror
    </div>

    {{-- 商品の状態（✅ここだけ修正：condition -> condition_id / DBのconditions） --}}
    <div style="margin-bottom:30px;">
      <div style="font-weight:bold; margin-bottom:10px;">商品の状態</div>

      <select name="condition_id" style="
        width:100%;
        height:40px;
        border:1px solid #ccc;
        border-radius:4px;
        padding:0 10px;
        background:#fff;
      ">
        <option value="">選択してください</option>
        @foreach($conditions as $condition)
          <option value="{{ $condition->id }}" {{ (string)old('condition_id') === (string)$condition->id ? 'selected' : '' }}>
            {{ $condition->name }}
          </option>
        @endforeach
      </select>

      @error('condition_id')
        <div style="color:#ff4d4d; margin-top:8px;">{{ $message }}</div>
      @enderror
    </div>

    {{-- 商品名と説明 --}}
    <div style="font-weight:bold; font-size:18px; margin:10px 0 10px;">
      商品名と説明
    </div>
    <div style="border-top:1px solid #ccc; margin-bottom:20px;"></div>

    {{-- 商品名 --}}
    <div style="margin-bottom:18px;">
      <div style="font-weight:bold; margin-bottom:6px;">商品名</div>
      <input type="text" name="name" value="{{ old('name') }}" style="
        width:100%;
        height:40px;
        border:1px solid #ccc;
        border-radius:4px;
        padding:0 10px;
      ">
      @error('name')
        <div style="color:#ff4d4d; margin-top:8px;">{{ $message }}</div>
      @enderror
    </div>

    {{-- ブランド名 --}}
    <div style="margin-bottom:18px;">
      <div style="font-weight:bold; margin-bottom:6px;">ブランド名</div>
      <input type="text" name="brand" value="{{ old('brand') }}" style="
        width:100%;
        height:40px;
        border:1px solid #ccc;
        border-radius:4px;
        padding:0 10px;
      ">
      @error('brand')
        <div style="color:#ff4d4d; margin-top:8px;">{{ $message }}</div>
      @enderror
    </div>

    {{-- 商品の説明 --}}
    <div style="margin-bottom:22px;">
      <div style="font-weight:bold; margin-bottom:6px;">商品の説明</div>
      <textarea name="description" rows="5" style="
        width:100%;
        border:1px solid #ccc;
        border-radius:4px;
        padding:10px;
        resize:vertical;
      ">{{ old('description') }}</textarea>
      @error('description')
        <div style="color:#ff4d4d; margin-top:8px;">{{ $message }}</div>
      @enderror
    </div>

    {{-- 販売価格 --}}
    <div style="margin-bottom:34px;">
      <div style="font-weight:bold; margin-bottom:6px;">販売価格</div>
      <div style="display:flex; align-items:center; gap:8px;">
        <div style="font-weight:bold;">¥</div>
        <input type="number" name="price" value="{{ old('price') }}" style="
          width:100%;
          height:40px;
          border:1px solid #ccc;
          border-radius:4px;
          padding:0 10px;
        ">
      </div>
      @error('price')
        <div style="color:#ff4d4d; margin-top:8px;">{{ $message }}</div>
      @enderror
    </div>

    {{-- 出品ボタン --}}
    <button type="submit" style="
      width:100%;
      height:48px;
      background:#ff4d4d;
      border:none;
      border-radius:4px;
      color:#fff;
      font-weight:bold;
      cursor:pointer;
      font-size:16px;
    ">
      出品する
    </button>

  </form>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('imageInput');
    const fileName = document.getElementById('fileName');
    const preview = document.getElementById('imagePreview');

    if (!input) return;

    input.addEventListener('change', () => {
      const file = input.files && input.files[0];

      if (!file) {
        fileName.textContent = '選択されていません';
        preview.style.display = 'none';
        preview.src = '';
        return;
      }

      fileName.textContent = file.name;
      input.closest('label').childNodes[0].textContent = '画像を変更する';

      // 画像以外が来たらプレビューしない（保険）
      if (!file.type.startsWith('image/')) {
        preview.style.display = 'none';
        preview.src = '';
        return;
      }

      const reader = new FileReader();
      reader.onload = (e) => {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    });
  });
</script>
@endsection