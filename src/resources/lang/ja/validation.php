<?php

return [
    'required'  => ':attribute を入力してください。',
    'email'     => ':attribute はメール形式で入力してください。',
    'confirmed' => ':attribute と一致しません。',
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],
    'max' => [
        'string' => ':attribute は :max 文字以内で入力してください。',
    ],
    'unique' => ':attributeは既に使用されています。',

    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード',
        'name' => 'お名前',
        'payment_method' => '支払い方法',
        'comment' => '商品コメント',
        'postal_code' => '郵便番号',
        'address' => '住所',
        'image' => '画像',
        'price' => '商品価格',
    ],
];