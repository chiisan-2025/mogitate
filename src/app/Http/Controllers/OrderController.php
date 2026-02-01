<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Item $item)
    {
        // 売却済みガード
        if ($item->is_sold) {
            abort(404);
        }

        $userId = Auth::id();

        $profile = Profile::where('user_id', $userId)->first();

        if (!$profile || !$profile->postal_code || !$profile->address) {
            // 本来はプロフィール編集へ
            return back();
        }

        DB::transaction(function () use ($item, $userId, $profile) {

            // ここで最新状態をロックして取り直す（競合に強くなる）
            $lockedItem = Item::where('id', $item->id)->lockForUpdate()->first();

            if ($lockedItem->is_sold) {
                abort(404);
            }

            Order::create([
                'user_id' => $userId,
                'item_id' => $lockedItem->id,
                'postal_code' => $profile->postal_code,
                'address' => $profile->address,
                'building' => $profile->building,
                'payment_method' => null,
            ]);

            $lockedItem->update(['is_sold' => true]);
        });

        return redirect()->route('items.index');
    }
}
