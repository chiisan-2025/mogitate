<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Profile;
use App\Models\Item;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Http\Requests\PurchaseRequest;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Item $item)
    {
        if ($item->is_sold || Order::where('item_id', $item->id)->exists()) {
            abort(404);
        }

        if ($item->user_id === Auth::id()) {
            return redirect()->route('items.show', $item)
                ->with('error', '自分が出品した商品は購入できません');
        }

        $profile = Profile::where('user_id', Auth::id())->first();

        return view('orders.create', compact('item', 'profile'));
    }

    public function store(PurchaseRequest $request, Item $item)
    {
        $userId = Auth::id();

        if ($item->user_id === $userId) {
            return redirect()->route('items.show', $item)
                ->with('error', '自分が出品した商品は購入できません');
        }

        $validated = $request->validated();
        $paymentMethod = $validated['payment_method'];

        $profile = Profile::where('user_id', $userId)->first();
        if (!$profile || !$profile->postal_code || !$profile->address) {
            return redirect()->back()->with('error', '購入にはプロフィール（住所）の登録が必要です');
        }

        try {
            DB::transaction(function () use ($item, $userId, $profile, $paymentMethod) {
                $lockedItem = Item::where('id', $item->id)->lockForUpdate()->firstOrFail();

                if ($lockedItem->is_sold || Order::where('item_id', $lockedItem->id)->exists()) {
                    throw new \RuntimeException('sold');
                }

                if ($lockedItem->user_id === $userId) {
                    throw new \RuntimeException('own');
                }

                Order::create([
                    'user_id' => $userId,
                    'item_id' => $lockedItem->id,
                    'postal_code' => $profile->postal_code,
                    'address' => $profile->address,
                    'building' => $profile->building,
                    'payment_method' => $paymentMethod,
                ]);

                $lockedItem->update(['is_sold' => true]);
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'sold') {
                return redirect()->route('items.show', $item)
                    ->with('error', 'この商品は売り切れです');
            }
            if ($e->getMessage() === 'own') {
                return redirect()->route('items.show', $item)
                    ->with('error', '自分が出品した商品は購入できません');
            }
            throw $e;
        }

        if ($paymentMethod === 'card') {
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => (int) $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'success_url' => url('/profile?tab=buy'),
                'cancel_url'  => route('items.show', $item) . '?canceled=1',
            ]);

            return redirect()->away($session->url);
        }

        return redirect()->to('/profile?tab=buy')
            ->with('success', '注文が完了しました（コンビニ支払い）');
    }
}