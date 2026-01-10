<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use Database\Seeders\ConditionSeeder;
use Database\Seeders\CategorySeeder;

class ListingTest extends TestCase
{
    use RefreshDatabase;

    //商品出品で必要な情報が保存されること
    public function test_user_can_store_item()
    {
        $this->seed(ConditionSeeder::class);
        $this->seed(CategorySeeder::class);

        $user = User::factory()->create();

        $condition = Condition::first();
        $this->assertNotNull($condition);

        $categoryIds = Category::take(2)->pluck('id')->toArray();
        $this->assertNotEmpty($categoryIds);

        Storage::fake();

        $image = UploadedFile::fake()->createWithContent(
            'item.png',
            file_get_contents(public_path('images/sample.png'))
        );

        $payload = [
            'name'        => 'テスト商品',
            'brand'       => 'テストブランド',
            'description' => 'テスト説明',
            'price'       => 5000,
            'condition'   => (string) $condition->id,
            'categories'  => array_map('strval', $categoryIds),
            'item_img'    => $image,
        ];

        $response = $this->actingAs($user)->post(route('item.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseCount('items', 1);

        $this->assertDatabaseHas('items', [
            'user_id'      => $user->id,
            'name'         => 'テスト商品',
            'brand'        => 'テストブランド',
            'description'  => 'テスト説明',
            'price'        => 5000,
            'condition_id' => $condition->id,
        ]);

        $item = Item::latest('id')->first();
        $this->assertNotNull($item);

        $filename = basename($item->item_img);

        Storage::disk('local')->assertExists("public/item_images/{$filename}");

    }
}