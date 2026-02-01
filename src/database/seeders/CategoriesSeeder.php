<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'レディース'],
            ['name' => 'メンズ'],
            ['name' => '家電'],
            ['name' => '本・音楽・ゲーム'],
            ['name' => 'スポーツ・レジャー']
        ];

        DB::table('categories')->insert($categories);
    }
}
