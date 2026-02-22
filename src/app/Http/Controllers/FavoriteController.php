<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Item $item)
    {
        $userId = Auth::id();
        if (!$userId) abort(403);

        // 二重登録防止（DB unique あるけど、コードでも安全に）
        Favorite::firstOrCreate([
            'user_id' => $userId,
            'item_id' => $item->id,
        ]);

        return back()->with('success', 'お気に入りしました');
    }

    public function destroy(Item $item)
    {
        $userId = Auth::id();

        Favorite::where('user_id', $userId)
            ->where('item_id', $item->id)
            ->delete();

        return back()->with('success', 'お気に入りを解除しました');
    }
}