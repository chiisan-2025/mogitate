<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\Order;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'image_path',
        'is_sold',
    ];

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
