<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;

class ItemsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $items = [
            [
                'name' => '腕時計',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'image_path' => 'items/watch.jpg',
            ],
            [
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'image_path' => 'items/hdd.jpg',
            ],
            [
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'image_path' => 'items/onion.jpg',
            ],
            [
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'image_path' => 'items/shoes.jpg',
            ],
            [
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'image_path' => 'items/laptop.jpg',
            ],
            [
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'image_path' => 'items/mic.jpg',
            ],
            [
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'image_path' => 'items/bag.jpg',
            ],
            [
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'image_path' => 'items/tumbler.jpg',
            ],
            [
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミルです。',
                'price' => 4000,
                'image_path' => 'items/mill.jpg',
            ],
            [
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'image_path' => 'items/makeup.jpg',
            ],
        ];

        foreach ($items as $item) {
            Item::create([
                'user_id' => $user->id,
                'name' => $item['name'],
                'description' => $item['description'],
                'price' => $item['price'],
                'image_path' => $item['image_path'],
                'is_sold' => false,
            ]);
        }
    }
}