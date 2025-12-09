<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // store(POST)：画像必須、update(PUT/PATCH)：画像任意
        $imageRule = $this->isMethod('post') ? 'required' : 'nullable';

        return [
            'name'         => 'required|string|max:255',
            'price'        => 'required|integer|between:0,10000',
            'image'        => $imageRule . '|image|mimes:png,jpeg',
            'description'  => 'required|string|max:120',
            'season_ids'   => 'required|array',
            'season_ids.*' => 'exists:seasons,id',
        ];
    }

    // 項目名（仕様書では個別の文言を使うため attributes は使わない）
    public function attributes()
    {
        return [];
    }

    public function messages()
    {
        return [
            // 商品名
            'name.required'        => '商品名を入力してください',

            // 値段
            'price.required'       => '値段を入力してください',
            'price.integer'        => '数値で入力してください',
            'price.between'        => '0〜10000円以内で入力してください',

            // 画像
            'image.required'       => '画像を登録してください',
            'image.mimes'          => '「.png」または「.jpeg」形式でアップロードしてください',

            // 季節
            'season_ids.required'  => '季節を選択してください',

            // 商品説明
            'description.required' => '商品説明を入力してください',
            'description.max'      => '120文字以内で入力してください',
        ];
    }
}