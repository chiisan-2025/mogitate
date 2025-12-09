<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'description',
    ];

    // ★ ここが今回追加するリレーション
    public function seasons()
    {
        return $this->belongsToMany(Season::class);
        // デフォルトで product_season テーブル、
        // product_id / season_id を使ってくれる
    }
}

