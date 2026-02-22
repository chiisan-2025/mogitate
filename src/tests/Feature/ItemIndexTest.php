<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_items_are_displayed()
    {
        Item::factory()->create(['name' => 'Apple']);
        Item::factory()->create(['name' => 'Banana']);

        $response = $this->get(route('items.index'));

        $response->assertStatus(200);
        $response->assertSee('Apple');
        $response->assertSee('Banana');
    }

    public function test_sold_item_shows_sold_label()
    {
        Item::factory()->create([
            'name' => 'Coffee',
            'is_sold' => 1, // ← boolより安全
        ]);

        $response = $this->get(route('items.index'));

        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    public function test_user_cannot_see_own_items_in_recommend_tab()
    {
        $user = User::factory()->create();

        Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'My Item',
        ]);

        $response = $this->actingAs($user)->get(route('items.index'));

        $response->assertStatus(200);
        $response->assertDontSee('My Item');
    }
}