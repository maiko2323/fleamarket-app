<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\SoldItem;
use Database\Seeders\ConditionSeeder;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // マイページに必要な情報が表示されること
    public function test_user_profile_page_displays_required_information()
    {
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $user->profile()->create([
            'post_code' => '123-4567',
            'address' => 'テスト住所',
            'building_name' => 'テストビル',
            'image_url' => 'images/default-icon.png',
        ]);

        $condition = Condition::first();

        $listedItem = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => '出品した商品',
        ]);

        $seller = User::factory()->create();
        $purchasedItem = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '購入した商品',
        ]);

        SoldItem::create([
            'item_id' => $purchasedItem->id,
            'buyer_id' => $user->id,
            'payment_method'=> 'カード払い',
            'post_code'     => '123-4567',
            'address'       => 'テスト住所',
            'building_name' => 'テストビル',
            'sold_at'       => now(),
        ]);

        $response = $this->actingAs($user)->get('/mypage');
        $response->assertStatus(200);
        $response->assertSeeText('テストユーザー');
        $response->assertSeeText($listedItem->name);

        $response->assertSeeText($purchasedItem->name);
        $response->assertSee('images/default-icon.png');
    }

    // プロフィール編集画面に初期値が表示されること
    public function test_profile_edit_page_shows_initial_values()
    {
        $user = User::factory()->create([
            'name' => '初期ユーザー名',
        ]);

        $user->profile()->create([
            'post_code' => '111-2222',
            'address' => '初期住所',
            'building_name' => '初期ビル',
            'image_url' => 'images/default-icon.png',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);

        $response->assertSee('value="初期ユーザー名"', false);
        $response->assertSee('value="111-2222"', false);
        $response->assertSee('初期住所');
        $response->assertSee('初期ビル');
    }

}
