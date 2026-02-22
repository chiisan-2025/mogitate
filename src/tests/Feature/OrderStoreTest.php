<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_purchase_and_is_redirected_to_login()
    {
        $condition = Condition::create(['name' => '良好']);
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand' => 'brand',
            'description' => 'desc',
            'price' => 1000,
            'image_path' => 'items/a.jpg',
            'is_sold' => false,
        ]);

        $res = $this->post(route('orders.store', $item), [
            'payment_method' => 'convenience',
        ]);

        $res->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_purchase_item_and_item_becomes_sold_and_order_is_created()
    {
        $condition = Condition::create(['name' => '良好']);

        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        // 住所必須ロジック対策（あなたのOrderControllerに合わせる）
        Profile::create([
            'user_id' => $buyer->id,
            'postal_code' => '123-4567',
            'address' => '東京都テスト1-2-3',
            'building' => 'ビル101',
            'icon_path' => null,
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '購入される商品',
            'brand' => 'brand',
            'description' => 'desc',
            'price' => 7000,
            'image_path' => 'items/b.jpg',
            'is_sold' => false,
        ]);

        $res = $this->actingAs($buyer)->post(route('orders.store', $item), [
            'payment_method' => 'convenience',
        ]);

        // あなたのコードは /profile?tab=buy に飛ばしてるのでここで確認
        $res->assertRedirect('/profile?tab=buy');

        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'convenience',
        ]);

        $this->assertTrue($item->fresh()->is_sold);
    }

    /** @test */
    public function user_cannot_purchase_own_item()
    {
        $condition = Condition::create(['name' => '良好']);
        $user = User::factory()->create();

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都テスト1-2-3',
            'building' => null,
            'icon_path' => null,
        ]);

        $item = Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => '自分の商品',
            'brand' => 'brand',
            'description' => 'desc',
            'price' => 1000,
            'image_path' => 'items/c.jpg',
            'is_sold' => false,
        ]);

        $res = $this->actingAs($user)->post(route('orders.store', $item), [
            'payment_method' => 'convenience',
        ]);

        $res->assertRedirect(route('items.show', $item));
        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
        $this->assertFalse($item->fresh()->is_sold);
    }

    /** @test */
        public function double_purchase_is_prevented()
        {
            $condition = Condition::create(['name' => '良好']);

            $buyer1 = User::factory()->create();
            $buyer2 = User::factory()->create();
            $seller = User::factory()->create();

            Profile::create([
                'user_id' => $buyer1->id,
                'postal_code' => '123-4567',
                'address' => '東京都テスト1-2-3',
                'building' => null,
                'icon_path' => null,
            ]);

            Profile::create([
                'user_id' => $buyer2->id,
                'postal_code' => '123-4567',
                'address' => '東京都テスト1-2-3',
                'building' => null,
                'icon_path' => null,
            ]);

            $item = Item::create([
                'user_id' => $seller->id,
                'condition_id' => $condition->id,
                'name' => '二重購入されない商品',
                'brand' => 'brand',
                'description' => 'desc',
                'price' => 5000,
                'image_path' => 'items/d.jpg',
                'is_sold' => false,
            ]);

        // 1回目購入
            $this->actingAs($buyer1)->post(route('orders.store', $item), [
                'payment_method' => 'convenience',
            ])->assertRedirect('/profile?tab=buy');

        // 2回目購入（弾かれる：あなたのコードは sold の時 items.show に error で戻す）
            $res2 = $this->actingAs($buyer2)->post(route('orders.store', $item), [
                'payment_method' => 'convenience',
            ]);

            $res2->assertRedirect(route('items.show', $item));

        // order は1件だけ
            $this->assertEquals(1, Order::where('item_id', $item->id)->count());
        }

    public function test_purchased_item_is_labeled_sold_on_index(): void
    {
        $buyer = User::factory()->create(['email_verified_at' => now()]);
        $seller = User::factory()->create(['email_verified_at' => now()]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
            'name' => 'SOLD確認商品',
        ]);

    // 購入
        $this->actingAs($buyer)->post(route('orders.store', $item), [
            'postal_code' => '123-4567',
            'address' => '東京都',
            'building' => 'ビル',
            'payment_method' => null, // nullableなのでOK（現状仕様）
        ])->assertRedirect(); // 先は実装により差があるのでOK

    // 一覧で SOLD 表示
        $response = $this->get(route('items.index'));
        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    public function test_purchased_item_appears_in_profile_buy_list(): void
    {
        $buyer  = User::factory()->create(['email_verified_at' => now()]);
        $seller = User::factory()->create(['email_verified_at' => now()]);

        Profile::create([
            'user_id' => $buyer->id,
            'postal_code' => '123-4567',
            'address' => '東京都',
            'building' => null,
            'icon_path' => null,
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
            'name' => '購入一覧に出る商品',
        ]);

    // 購入（payment_method 必須）
        $this->actingAs($buyer)->post(route('orders.store', $item), [
            'payment_method' => 'convenience',
        ])->assertRedirect('/profile?tab=buy');

        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

    // プロフィール（buyタブ）に表示される
        $res = $this->actingAs($buyer)->get(route('profile.show', ['tab' => 'buy']));
        $res->assertStatus(200);
        $res->assertSee('購入一覧に出る商品');
    }
}
