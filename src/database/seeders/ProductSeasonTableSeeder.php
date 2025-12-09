<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeasonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_season')->insert([
            // 1: キウイ  → 秋・冬
            ['product_id' => 1, 'season_id' => 3],
            ['product_id' => 1, 'season_id' => 4],

            // 2: ストロベリー → 春
            ['product_id' => 2, 'season_id' => 1],

            // 3: オレンジ → 冬
            ['product_id' => 3, 'season_id' => 4],

            // 4: スイカ → 夏
            ['product_id' => 4, 'season_id' => 2],

            // 5: ピーチ → 夏
            ['product_id' => 5, 'season_id' => 2],

            // 6: シャインマスカット → 夏・秋
            ['product_id' => 6, 'season_id' => 2],
            ['product_id' => 6, 'season_id' => 3],

            // 7: パイナップル → 春・夏
            ['product_id' => 7, 'season_id' => 1],
            ['product_id' => 7, 'season_id' => 2],

            // 8: ブドウ → 夏・秋
            ['product_id' => 8, 'season_id' => 2],
            ['product_id' => 8, 'season_id' => 3],

            // 9: バナナ → 夏
            ['product_id' => 9, 'season_id' => 2],

            // 10: メロン → 春・夏
            ['product_id' => 10, 'season_id' => 1],
            ['product_id' => 10, 'season_id' => 2],
        ]);
    }
}