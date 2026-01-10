<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\SoldItem;
use App\Models\Category;
use App\Models\Comment;
use Database\Seeders\ConditionSeeder;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    // トップページに全商品が表示されること
    public function test_items_displayed_on_top_page()
    {
        $this->seed(ConditionSeeder::class);

        $seller = User::factory()->create();
        $items = Item::factory()->count(3)->create([
            'user_id' => $seller->id,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewHas('items', function ($viewItems) use ($items) {
        return $viewItems->count() === $items->count();
        });
    }

    // 購入済みの商品に「Sold」ラベルが表示されること
    public function test_sold_label_is_displayed_for_purchased_items()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $condition = Condition::create([
        'label' => '良好',
        ]);

        $soldItem = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
        ]);

        $normalItem = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
        ]);

        SoldItem::create([
            'item_id'       => $soldItem->id,
            'buyer_id'      => $buyer->id,
            'payment_method'=> 'カード払い',
            'post_code'     => '123-4567',
            'address'       => 'テスト住所',
            'building_name' => 'テストビル',
            'sold_at'       => now(),
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('売り切れ');
        $response->assertSeeText($soldItem->name);
        $response->assertSeeText($normalItem->name);
    }

    // ログインユーザーの出品商品がトップページに表示されないこと
    public function test_own_items_are_not_shown_in_top_page_for_logged_in_user()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $condition = Condition::create([
        'label' => '良好',
        ]);

        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        $otherItem = Item::factory()->create([
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertDontSeeText($myItem->name);
        $response->assertSeeText($otherItem->name);
    }

    // マイリストにはいいねした商品のみ表示されること
    public function test_only_liked_items_are_shown_in_mylist()
    {
        $user = User::factory()->create();
        $condition = Condition::create(['label' => '良好']);

        $likedItem = Item::factory()->create([
            'user_id'      => $user->id,
            'condition_id' => $condition->id,
        ]);
        $otherItem = Item::factory()->create([
            'condition_id' => $condition->id,
        ]);
        DB::table('likes')->insert([
        'user_id' => $user->id,
        'item_id' => $likedItem->id,
        'created_at' => now(),
        'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertSeeText($likedItem->name);
        $response->assertDontSeeText($otherItem->name);
    }

    // マイリストで購入済み商品に「Sold」ラベルが表示されること
    public function test_sold_label_is_shown_for_purchased_item_in_mylist()
    {
        $seller = User::factory()->create();
        $buyer  = User::factory()->create();
        $condition = Condition::create(['label' => '良好']);

        $soldItem = Item::factory()->create([
            'user_id'      => $seller->id,
            'condition_id' => $condition->id,
        ]);

        DB::table('likes')->insert([
            'user_id'    => $buyer->id,
            'item_id'    => $soldItem->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        SoldItem::create([
            'item_id'  => $soldItem->id,
            'buyer_id' => $buyer->id,
            'payment_method'=> 'カード払い',
            'post_code'     => '123-4567',
            'address'       => 'テスト住所',
            'building_name' => 'テストビル',
            'sold_at'       => now(),
        ]);

        $response = $this->actingAs($buyer)->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertSee($soldItem->name);
        $response->assertSee('売り切れ');
    }

    // 未認証ユーザーはマイリストに何も表示されないこと
    public function test_guest_sees_no_items_in_mylist()
    {
        $user = User::factory()->create();
        $condition = Condition::create(['label' => '良好']);

        $likedItem = Item::factory()->create([
            'user_id'      => $user->id,
            'condition_id' => $condition->id,
        ]);

        DB::table('likes')->insert([
            'user_id'    => $user->id,
            'item_id'    => $likedItem->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertDontSeeText($likedItem->name);
    }

    // 商品が部分一致で検索できること
    public function test_items_can_be_searched_by_partial_name()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        $seller = User::factory()->create();
        $condition = Condition::first();
        $targetItem = Item::factory()->create([
            'user_id'      => $seller->id,
            'condition_id' => $condition->id,
            'name'         => 'ナイキ スニーカー 黒',
        ]);

        $otherItem = Item::factory()->create([
            'user_id'      => $seller->id,
            'condition_id' => $condition->id,
            'name'         => 'アディダス ジャケット',
        ]);

        $response = $this->get('/?keyword=スニーカー');

        $response->assertStatus(200);
        $response->assertSeeText($targetItem->name);
        $response->assertDontSeeText($otherItem->name);
    }

    // マイリストタブでも検索キーワードが保持されること
    public function test_search_keyword_is_preserved_on_mylist_tab()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        $user = User::factory()->create();
        $condition = Condition::first();

        $likedMatchedItem = Item::factory()->create([
        'user_id'      => $user->id,
        'condition_id' => $condition->id,
        'name'         => 'ナイキ スニーカー 黒',
        ]);

        $likedUnmatchedItem = Item::factory()->create([
            'user_id'      => $user->id,
            'condition_id' => $condition->id,
            'name'         => 'アディダス ジャケット',
        ]);

        DB::table('likes')->insert([
            'user_id'   => $user->id,
            'item_id'   => $likedMatchedItem->id,
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
        DB::table('likes')->insert([
            'user_id'   => $user->id,
            'item_id'   => $likedUnmatchedItem->id,
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=スニーカー');

        $response->assertStatus(200);

        $response->assertSeeText($likedMatchedItem->name);
        $response->assertDontSeeText($likedUnmatchedItem->name);
    }

    // 商品詳細ページに必要な情報がすべて表示されること
    public function test_item_detail_page_displays_all_required_information()
    {
        $seller = User::factory()->create();
        $condition = Condition::create(['label' => '良好']);

        $category1 = Category::create(['name' => 'メンズ']);
        $category2 = Category::create(['name' => 'ファッション']);

        $item = Item::factory()->create([
            'user_id'      => $seller->id,
            'condition_id' => $condition->id,
            'name'         => 'テスト商品',
            'brand'        => 'NIKE',
            'description'  => 'これはテスト商品の説明です。',
            'price'        => 5000,
            'item_img'     => 'images/sample.png',
        ]);

        $item->categories()->attach([$category1->id, $category2->id]);

        DB::table('likes')->insert([
        'user_id'    => $seller->id,
        'item_id'    => $item->id,
        'created_at' => now(),
        'updated_at' => now(),
        ]);

        $commentUser = User::factory()->create();
        Comment::create(['user_id'=>$commentUser->id,'item_id'=>$item->id,'content'=>'コメント1']);
        Comment::create(['user_id'=>$commentUser->id,'item_id'=>$item->id,'content'=>'コメント2']);

        $response = $this->get(route('item.show', $item->id));

        $response->assertStatus(200);
        $response->assertSeeText('テスト商品');
        $response->assertSeeText('NIKE');
        $response->assertSeeText('（税込）');
        $response->assertSee($item->item_img);

        $response->assertSeeText('メンズ');
        $response->assertSeeText('ファッション');

        $response->assertSeeText('良好');

        $response->assertSeeText('コメント1');
        $response->assertSeeText('コメント2');
    }

    // 商品詳細ページに複数カテゴリが表示されること
    public function test_multiple_categories_are_displayed_on_item_detail_page()
    {
        $seller    = User::factory()->create();
        $condition = Condition::create(['label' => '良好']);

        $categoryA = Category::create(['name' => 'スポーツ']);
        $categoryB = Category::create(['name' => 'インテリア']);
        $categoryC = Category::create(['name' => 'おもちゃ']);
        $item = Item::factory()->create([
            'user_id'=>$seller->id,
            'condition_id'=>$condition->id,
            'name'=>'カテゴリテスト商品',
        ]);

        $item->categories()->attach([$categoryA->id,$categoryB->id,$categoryC->id]);

        $response = $this->get(route('item.show', $item->id));

        $response->assertStatus(200);
        $response->assertSeeText('スポーツ');
        $response->assertSeeText('インテリア');
        $response->assertSeeText('おもちゃ');
    }

    // いいねアイコンを押下したら登録され、いいね数が増えること
    public function test_user_can_like_item_and_like_count_increases()
    {
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create();
        $condition = Condition::first();
        $item = Item::factory()->create([
            'user_id'      => $user->id,
            'condition_id' => $condition->id,
            'name'         => 'ナイキ スニーカー',
        ]);

        $this->actingAs($user)->post(route('items.like', ['item' => $item->id]));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get(route('item.show', $item->id));
        $response->assertStatus(200);
        $response->assertSeeText('1');
    }

    // いいねした商品はいいね済みアイコンが表示されること
    public function test_liked_item_shows_liked_icon()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        $user = \App\Models\User::factory()->create();
        $condition = \App\Models\Condition::first();

        $item = \App\Models\Item::factory()->create([
            'user_id'      => $user->id,
            'condition_id' => $condition->id,
            'name'         => 'ナイキ スニーカー',
        ]);

        \Illuminate\Support\Facades\DB::table('likes')->insert([
            'user_id'    => $user->id,
            'item_id'    => $item->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('item.show', $item->id));

        $response->assertStatus(200);


        $response->assertSee('images/liked.svg');
        $response->assertDontSee('images/unliked.svg');
    }

    // 再度押すといいね解除、いいね数が減ること
    public function test_user_can_unlike_item_and_like_count_decreases()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        $user = \App\Models\User::factory()->create();
        $condition = \App\Models\Condition::first();

        $item = \App\Models\Item::factory()->create([
            'user_id'      => $user->id,
            'condition_id' => $condition->id,
            'name'         => 'ナイキ スニーカー',
        ]);

        $this->actingAs($user)->post(route('items.like', ['item' => $item->id]));
        $this->actingAs($user)->post(route('items.like', ['item' => $item->id]));

        $response = $this->actingAs($user)->get(route('item.show', $item->id));
        $response->assertStatus(200);

        $response->assertSee('<span class="stat-count like-count">0</span>', false);

        $response->assertSee('images/unliked.svg');
        $response->assertDontSee('images/liked.svg');
    }

    // ログイン済みユーザーはコメントを投稿できること
    public function test_authenticated_user_can_post_comment()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

            $user = \App\Models\User::factory()->create();
            $condition = \App\Models\Condition::first();
        $item = \App\Models\Item::factory()->create([
            'condition_id' => $condition->id,
        ]);

        $before = \Illuminate\Support\Facades\DB::table('comments')->count();

        $response = $this->actingAs($user)->post(
            route('comment.store', ['item' => $item->id]),
            ['content' => 'テストコメントです']
    );

        $response->assertStatus(302);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメントです',
        ]);

        $after = \Illuminate\Support\Facades\DB::table('comments')->count();
        $this->assertSame($before + 1, $after);
    }

    // ログイン前のユーザーはコメントを投稿できないこと
    public function test_guest_cannot_post_comment()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        $condition = \App\Models\Condition::first();

        $item = \App\Models\Item::factory()->create([
        'condition_id' => $condition->id,
        ]);

        $before = \Illuminate\Support\Facades\DB::table('comments')->count();

        $response = $this->post(
            route('comment.store', ['item' => $item->id]),
            ['content' => 'ゲストコメント']
    );
        $response->assertRedirect('/login');

        $after = \Illuminate\Support\Facades\DB::table('comments')->count();
        $this->assertSame($before, $after);
    }

    // コメント未入力だとバリデーションエラーになること
    public function test_comment_is_required_validation()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        $user = \App\Models\User::factory()->create();
        $condition = \App\Models\Condition::first();

        $item = \App\Models\Item::factory()->create([
            'condition_id' => $condition->id,
        ]);

        $response = $this->actingAs($user)->post(
            route('comment.store', ['item' => $item->id]),
            ['content' => '']
        );

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['content']);
    }

    // コメントが256文字以上だとバリデーションエラーになること
    public function test_comment_max_length_validation()
    {
    $this->seed(\Database\Seeders\ConditionSeeder::class);

        $user = \App\Models\User::factory()->create();
        $condition = \App\Models\Condition::first();
        $item = \App\Models\Item::factory()->create([
            'condition_id' => $condition->id,
        ]);

        $tooLong = str_repeat('a', 256);

        $response = $this->actingAs($user)->post(
            route('comment.store', ['item' => $item->id]),
            ['content' => $tooLong]
        );

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['content']);
    }

}