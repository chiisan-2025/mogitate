<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_store_item_with_image_and_categories_and_redirect_to_profile_sell_tab()
    {
         $this->withoutExceptionHandling();
        // 画像保存先をフェイク（storage/app/public 配下）
        Storage::fake('public');

        // ログインユーザー
        $user = User::factory()->create();

        // 条件（items.condition_id が NOT NULL なので必須）
        $condition = Condition::create(['name' => '良好']);

        // カテゴリ複数（pivot: category_item）
        $cat1 = Category::create(['name' => 'レディース']);
        $cat2 = Category::create(['name' => '家電']);

        // アップロード画像
        $image = UploadedFile::fake()->image('item.jpg');

        // 送信（store() のバリデーションに合わせて key を揃える）
        $response = $this->actingAs($user)->post(route('items.store'), [
            'name' => 'コーヒー',
            'brand' => 'Kaldi',
            'description' => 'テスト出品です',
            'price' => 7000,
            'image' => $image,
            'condition_id' => $condition->id,
            'categories' => [$cat1->id, $cat2->id],
        ]);

        $response->assertStatus(302);
            dump('Location:', $response->headers->get('Location'));
            dump('Errors:', session('errors')?->all());

        // リダイレクト先（あなたのコード前提）
        $response->assertRedirect(route('profile.show', ['tab' => 'sell']));

        // items に保存されたか
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'コーヒー',
            'brand' => 'Kaldi',
            'description' => 'テスト出品です',
            'price' => 7000,
            'condition_id' => $condition->id,
        ]);

        $item = Item::where('name', 'コーヒー')->first();
        $this->assertNotNull($item);

        // 画像が public/items/... に保存されたか
        $this->assertStringStartsWith('items/', $item->image_path);
        Storage::disk('public')->assertExists($item->image_path);

        // pivot（category_item）に紐付いたか
        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $cat1->id,
        ]);
        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $cat2->id,
        ]);
    }
}