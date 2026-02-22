<h1>購入確認</h1>
@if(session('error'))
  <p style="color:red;">{{ session('error') }}</p>
@endif

<h2>{{ $item->name }}</h2>
<p>¥{{ number_format($item->price) }}</p>

<hr>

<h3>配送先</h3>

@if($profile && $profile->postal_code && $profile->address)

<p>〒{{ $profile->postal_code }}</p>
<p>{{ $profile->address }}</p>
<p>{{ $profile->building }}</p>

<a href="{{ route('profile.edit', ['redirect' => '/' . request()->path()]) }}">
  配送先を変更する
</a>

<form method="POST" action="{{ route('orders.store', $item) }}">
  @csrf

  <h3>支払い方法</h3>

  <label>
    <input type="radio" name="payment_method" value="convenience">
    コンビニ支払い
  </label>

  <br>

  <label>
    <input type="radio" name="payment_method" value="card">
    カード支払い
  </label>

  @error('payment_method')
    <p style="color:red;">{{ $message }}</p>
  @enderror

  <br><br>

  <button type="submit">購入する</button>

</form>

@else
  <p style="color:red;">
    住所が登録されていません。
  </p>

    <a href="{{ route('profile.edit', ['redirect' => '/' . request()->path()]) }}">
    プロフィールを登録する
    </a>
@endif