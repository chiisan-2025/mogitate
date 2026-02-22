<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'integer', 'min:0'],
            'image' => ['required', 'image', 'max:2048'],
            'condition_id' => ['required', 'exists:conditions,id'],
            'categories' => ['required', 'array'],
            'categories.*' => ['exists:categories,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '商品名',
            'brand' => 'ブランド名',
            'description' => '商品説明',
            'price' => '商品価格',
            'image' => '商品画像',
            'condition_id' => '商品の状態',
            'categories' => 'カテゴリー',
            'categories.*' => 'カテゴリー',
        ];
    }
}