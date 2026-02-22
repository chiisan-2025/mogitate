@extends('layouts.app')

@section('content')

<h1>プロフィール編集</h1>

@if ($errors->any())
  <div style="color:red;">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('profile.update') }}">
  @csrf
  @method('PUT') {{-- ← updateなら必須（重要） --}}

  {{-- ★追加：ユーザー名（要件にある） --}}
  <div>
    <label>ユーザー名</label>
    <input type="text"
           name="name"
           value="{{ old('name', auth()->user()->name) }}">
  </div>

  <div>
    <label>郵便番号</label>
    <input type="text"
           name="postal_code"
           placeholder="123-4567"
           value="{{ old('postal_code', $profile->postal_code ?? '') }}">
  </div>

  <div>
    <label>住所</label>
    <input type="text"
           name="address"
           value="{{ old('address', $profile->address ?? '') }}">
  </div>

  <div>
    <label>建物名</label>
    <input type="text"
           name="building"
           value="{{ old('building', $profile->building ?? '') }}">
  </div>

  <button type="submit">保存</button>
</form>

<a href="{{ route('profile.show') }}">戻る</a>

@endsection