<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_mylist_and_is_redirected_to_login()
    {
        $res = $this->get(route('mylist.index'));
        $res->assertRedirect(route('login'));
    }

    /** @test */
    public function mylist_shows_only_favorited_items()
    {
        $condition = Condition::create(['name' => '良好']);

        $user = User::factory()->create();
        $seller = User::factory()->create();

        $itemA = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '表示される商品',
            'brand' => 'brand',
            'description' => 'desc',
            'price' => 1000,
            'image_path' => 'items/a.jpg',
            'is_sold' => false,
        ]);

        $itemB = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '表示されない商品',
            'brand' => 'brand',
            'description' => 'desc',
            'price' => 2000,
            'image_path' => 'items/b.jpg',
            'is_sold' => false,
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $itemA->id,
        ]);

        $res = $this->actingAs($user)->get(route('mylist.index'));

        $res->assertStatus(200);
        $res->assertSee('表示される商品');
        $res->assertDontSee('表示されない商品');
    }
}
