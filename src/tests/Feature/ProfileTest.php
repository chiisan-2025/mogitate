<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private function createCondition(): Condition
    {
        // conditions.name が unique なので firstOrCreate が安全
        return Condition::firstOrCreate(['name' => '良好']);
    }

    public function test_guest_cannot_access_profile_show_and_is_redirected_to_login(): void
    {
        $response = $this->get(route('profile.show'));

        $response->assertRedirect(route('login'));
    }

    public function test_unverified_user_is_redirected_to_verification_notice_on_profile_show(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('profile.show'));

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_verified_user_can_access_profile_show(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('profile.show'));

        $response->assertStatus(200);
        // 画面に必ず出そうな文言があればここで assertSee してOK（例："プロフィール"など）
    }

    public function test_profile_show_displays_sell_items_of_logged_in_user(): void
    {
        $condition = $this->createCondition();

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

    // 自分の出品（condition_id を固定！）
        Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'MY_SELL_ITEM',
        ]);

    // 他人の出品
        Item::factory()->create([
            'condition_id' => $condition->id,
            'name' => 'OTHER_SELL_ITEM',
        ]);

        $response = $this->actingAs($user)->get(route('profile.show', ['tab' => 'sell']));

        $response->assertStatus(200);
        $response->assertSee('MY_SELL_ITEM');
        $response->assertDontSee('OTHER_SELL_ITEM');
    }

    public function test_profile_show_displays_buy_items_of_logged_in_user(): void
    {
        $condition = $this->createCondition();

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => 'MY_BUY_ITEM',
        ]);

        Order::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get(route('profile.show', ['tab' => 'buy']));

        $response->assertStatus(200);
        $response->assertSee('MY_BUY_ITEM');
    }

    public function test_unverified_user_can_access_profile_edit(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
    }
}