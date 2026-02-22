<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_favorite_and_is_redirected_to_login()
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
            'image_path' => 'items/dummy.jpg',
            'is_sold' => false,
        ]);

        $res = $this->post(route('favorites.store', $item));
        $res->assertRedirect(route('login'));

        $this->assertDatabaseCount('favorites', 0);
    }

    /** @test */
    public function user_can_favorite_an_item()
    {
        $condition = Condition::create(['name' => '良好']);
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand' => 'brand',
            'description' => 'desc',
            'price' => 1000,
            'image_path' => 'items/dummy.jpg',
            'is_sold' => false,
        ]);

        $res = $this->actingAs($user)
            ->from(route('items.index'))
            ->post(route('favorites.store', $item));

        // FavoriteController は return back()
        $res->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function user_can_unfavorite_an_item()
    {
        $condition = Condition::create(['name' => '良好']);
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand' => 'brand',
            'description' => 'desc',
            'price' => 1000,
            'image_path' => 'items/dummy.jpg',
            'is_sold' => false,
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $res = $this->actingAs($user)
            ->from(route('items.index'))
            ->delete(route('favorites.destroy', $item));

        $res->assertRedirect(route('items.index'));

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function double_favorite_does_not_create_duplicates()
    {
        $condition = Condition::create(['name' => '良好']);
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand' => 'brand',
            'description' => 'desc',
            'price' => 1000,
            'image_path' => 'items/dummy.jpg',
            'is_sold' => false,
        ]);

        $this->actingAs($user)->post(route('favorites.store', $item));
        $this->actingAs($user)->post(route('favorites.store', $item)); // 2回目

        $this->assertEquals(1, Favorite::where('user_id', $user->id)->where('item_id', $item->id)->count());
    }
}