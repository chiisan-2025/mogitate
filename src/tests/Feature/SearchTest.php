<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_keyword_filters_items_on_recommend_tab()
    {
        Item::factory()->create(['name' => 'Apple']);
        Item::factory()->create(['name' => 'Banana']);

        $response = $this->get(route('items.index', ['keyword' => 'App']));

        $response->assertStatus(200);
        $response->assertSee('Apple');
        $response->assertDontSee('Banana');
    }

    public function test_keyword_filters_mylist_items_when_logged_in()
    {
        $user = User::factory()->create();

        $apple = Item::factory()->create(['name' => 'Apple']);
        $banana = Item::factory()->create(['name' => 'Banana']);

        // お気に入り登録（pivot: favorites）
        $user->favoriteItems()->attach($apple->id);
        $user->favoriteItems()->attach($banana->id);

        // mylistタブで検索
        $response = $this->actingAs($user)->get(route('items.index', [
            'tab' => 'mylist',
            'keyword' => 'App',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Apple');
        $response->assertDontSee('Banana');
    }

    public function test_tab_links_keep_keyword_query()
    {
        // keyword を付けてアクセスした時、タブリンクに keyword が含まれること
        $response = $this->get(route('items.index', ['keyword' => 'App']));

        $response->assertStatus(200);

        // recommend 側
        $response->assertSee('tab=recommend&amp;keyword=App', false);

        // mylist 側
        $response->assertSee('tab=mylist&amp;keyword=App', false);
    }
}
