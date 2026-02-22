<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\Order;

class Item extends Model
{
    use HasFactory;

    protected $casts = [
        'is_sold' => 'boolean',
    ];

    protected $fillable = [
        'user_id',
        'condition_id',
        'name',
        'brand',
        'description',
        'price',
        'image_path',
        'is_sold',
        'sold_at'
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) return null;

        // すでにURLならそのまま
        if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
            return $this->image_path;
        }

        // 例: images/sample1.jpg みたいに public 配下を想定するパス
        if (Str::startsWith($this->image_path, 'images/')) {
            return asset($this->image_path); // public/images/...
        }

        // それ以外は storage/public 側（items/xxx.jpg）
        return asset('storage/' . $this->image_path);
    }


    // 出品者（User）: Item belongsTo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // カテゴリ（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
        // pivot table: category_item
    }

    // お気に入り（1商品に複数のお気に入り）
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoredUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    // ログインユーザーがこの商品をお気に入り済みか
    public function isFavoritedBy(?User $user): bool
    {
        if (!$user) return false;

        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }

    // コメント（1商品に複数コメント）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 注文（1商品は1回だけ売れる前提）
    public function order()
    {
        return $this->hasOne(Order::class);
    }

}
