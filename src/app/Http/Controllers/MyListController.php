<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyListController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // auth()->user() と同じ

        // ✅ メール未認証なら空表示
        if (!$user->hasVerifiedEmail()) {
            $items = collect(); // 空のコレクション
            return view('mylist.index', compact('items'));
        }

        $keyword = $request->query('keyword');

        $items = $user->favoriteItems()
            ->when($keyword, fn($q) => $q->where('items.name', 'like', "%{$keyword}%"))
            ->latest('favorites.created_at')
            ->get();

        return view('mylist.index', compact('items'));
    }
}