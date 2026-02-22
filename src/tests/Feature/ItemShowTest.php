<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_detail_page_displays_name_description_and_price_format()
    {
        $item = Item::factory()->create([
            'name' => 'Coffee',
            'description' => 'Test description',
            'price' => 1000,
        ]);

        $response = $this->get(route('items.show', $item));

        $response->assertStatus(200);
        $response->assertSee('Coffee');
        $response->assertSee('Test description');

        // Blade: ¥{{ number_format($item->price) }}
        $response->assertSee('¥1,000', false);
    }

    public function test_item_detail_displays_categories()
    {
        $item = Item::factory()->create();

        $cat1 = Category::create(['name' => '家電']);
        $cat2 = Category::create(['name' => 'レディース']);

        $item->categories()->attach([$cat1->id, $cat2->id]);

        $response = $this->get(route('items.show', $item));

        $response->assertStatus(200);
        $response->assertSee('家電');
        $response->assertSee('レディース');
    }

    public function test_item_detail_displays_comments_user_name_and_comment_body()
    {
        $user = User::factory()->create(['name' => 'Ato']);
        $item = Item::factory()->create();

        Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'Nice item!',
        ]);

        $response = $this->get(route('items.show', $item));

        $response->assertStatus(200);
        $response->assertSee('Ato');
        $response->assertSee('Nice item!');
    }

    public function test_sold_item_shows_sold_label_for_guest()
    {
        $item = Item::factory()->create([
            'is_sold' => true,
        ]);

        $response = $this->get(route('items.show', $item));

        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    public function test_guest_sees_login_to_purchase_button_when_not_sold()
    {
        $item = Item::factory()->create([
            'is_sold' => false,
        ]);

        $response = $this->get(route('items.show', $item));

        $response->assertStatus(200);
        $response->assertSee('ログインして購入する');
    }

    public function test_owner_sees_own_item_message_when_logged_in()
    {
        $owner = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $owner->id,
            'is_sold' => false,
        ]);

        $response = $this->actingAs($owner)->get(route('items.show', $item));

        $response->assertStatus(200);
        $response->assertSee('自分が出品した商品です');
    }

    public function test_logged_in_user_sees_purchase_link_for_others_item_when_not_sold()
    {
        $owner = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $owner->id,
            'is_sold' => false,
        ]);

        $response = $this->actingAs($buyer)->get(route('items.show', $item));

        $response->assertStatus(200);
        $response->assertSee('購入手続きへ');
    }
}