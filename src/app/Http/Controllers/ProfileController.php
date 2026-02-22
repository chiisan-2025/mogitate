<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = auth()->user();
        $profile = Profile::firstOrCreate(['user_id' => $user->id]);

    // 出品した商品
        $sellItems = Item::where('user_id', $user->id)
            ->latest()
            ->get();

    // 購入した商品（orders → item）→ itemだけ配列にする
        $buyItems = Order::with('item')
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->pluck('item')
            ->filter(); // itemがnullの注文が混ざっても落ちない

        return view('profile.show', compact('profile', 'sellItems', 'buyItems', 'user'));
    }

    public function edit()
    {
        $profile = Profile::firstOrCreate(
            ['user_id' => auth()->id()],
            ['postal_code' => null, 'address' => null, 'building' => null, 'icon_path' => null]
        );

        return view('profile.edit', compact('profile'));
    }

    public function update(ProfileRequest $request)
    {
        $validated = $request->validated(); // ←最重要（rules + messages + attributes が反映される）

        $profile = Profile::firstOrCreate(['user_id' => auth()->id()]);
        $profile->update($validated);

        // redirect（購入確認から来た時など）
        if ($request->filled('redirect') && str_starts_with($request->redirect, '/')) {
            return redirect($request->redirect)
                ->with('success', 'プロフィールを更新しました');
        }

        return redirect()->route('profile.show')
            ->with('success', 'プロフィールを更新しました');
    }
}
