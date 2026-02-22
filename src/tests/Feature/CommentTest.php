<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_post_comment_and_is_redirected_to_login()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('comments.store', $item), [
            'comment' => 'ゲストコメント',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('comments', 0);
    }

    /** @test */
    public function verified_user_can_post_comment()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('comments.store', $item), [
            'comment' => 'テストコメント',
        ]);

        // store実装によって戻り先は items.show が多いので、ゆるく 302 だけ確認
        $response->assertStatus(302);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);
    }

    /** @test */
    public function user_cannot_delete_others_comment()
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $other = User::factory()->create(['email_verified_at' => now()]);
        $item  = Item::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $owner->id,
            'item_id' => $item->id,
            'comment' => 'owner comment',
        ]);

        $response = $this->actingAs($other)->delete(route('comments.destroy', $comment));

        // 実装が abort(403) か redirect のどっちでも拾えるようにする
        $this->assertTrue(in_array($response->status(), [302, 403], true));

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
        ]);
    }

    /** @test */
    public function user_can_delete_own_comment()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'my comment',
        ]);

        $response = $this->actingAs($user)->delete(route('comments.destroy', $comment));

        $response->assertStatus(302);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_comment_required_validation_message(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('items.show', $item))
            ->post(route('comments.store', $item), [
                'comment' => '',
            ]);

        $response->assertRedirect(route('items.show', $item));

        $response->assertSessionHasErrors([
            'comment' => '商品コメント を入力してください。',
        ]);
    }

    public function test_comment_max_validation_message(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $item = Item::factory()->create();

        $longText = str_repeat('a', 256);

        $response = $this->actingAs($user)
            ->from(route('items.show', $item))
            ->post(route('comments.store', $item), [
                'comment' => $longText,
            ]);

        $response->assertRedirect(route('items.show', $item));

        $response->assertSessionHasErrors([
            'comment' => '商品コメント は 255 文字以内で入力してください。',
        ]);
    }
}