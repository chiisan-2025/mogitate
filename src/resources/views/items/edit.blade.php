<h1>商品編集</h1>

@if ($errors->any())
  <div style="color:red;">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div>
    <label>商品名</label>
    <input type="text" name="name" value="{{ old('name', $item->name) }}">
  </div>

  <div>
    <label>説明</label>
    <textarea name="description">{{ old('description', $item->description) }}</textarea>
  </div>

  <div>
    <label>価格</label>
    <input type="number" name="price" value="{{ old('price', $item->price) }}">
  </div>

  <div>
    <label>商品画像（変更する場合のみ）</label>
    <input type="file" name="image">
  </div>

  <button type="submit">更新する</button>
</form>

<a href="{{ route('items.my') }}">戻る</a>