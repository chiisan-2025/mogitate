<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;

class ItemsSeeder extends Seeder
{
    public function run()
    {
        // ユーザーを必ず1人確保
        $user = User::first() ?? User::factory()->create();

        // カテゴリ取得
        $ladies = Category::where('name', 'レディース')->first();
        $mens   = Category::where('name', 'メンズ')->first();

        // Item 作成
        $item1 = Item::create([
            'user_id' => $user->id,
            'name' => 'サンプル商品1',
            'description' => 'サンプル商品の説明です。',
            'price' => 3000,
            'image_path' => 'images/sample1.jpg',
            'is_sold' => false,
        ]);

        $item2 = Item::create([
            'user_id' => $user->id,
            'name' => 'サンプル商品2',
            'description' => 'サンプル商品の説明です。',
            'price' => 4500,
            'image_path' => 'images/sample2.jpg',
            'is_sold' => false,
        ]);

        // N:N 紐付け
        $item1->categories()->sync([$ladies->id]);
        $item2->categories()->sync([$mens->id]);
    }
}