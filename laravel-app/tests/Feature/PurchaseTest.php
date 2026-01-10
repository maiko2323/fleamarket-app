<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Database\Seeders\ConditionSeeder;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\SoldItem;
use Mockery;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mockery::mock('alias:Stripe\Stripe')
            ->shouldReceive('setApiKey')
            ->andReturnNull();

    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // 購入ボタンを押すと購入が完了すること
    public function test_user_can_purchase_item()
    {
        $this->seed(ConditionSeeder::class);

        $seller = User::factory()->create();
        $buyer  = User::factory()->create();

        $buyer->profile()->create([
            'post_code' => '123-4567',
            'address' => 'テスト住所',
            'building_name' => 'テストビル',
        ]);

        $condition = Condition::first();

        $item = Item::factory()->create([
            'user_id'      => $seller->id,
            'condition_id' => $condition->id,
        ]);

        Mockery::mock('alias:Stripe\Checkout\Session')
            ->shouldReceive('create')
            ->once()
            ->andReturn((object)['url' => 'https://example.test/checkout']);

        $response = $this->actingAs($buyer)->post(
            route('purchase.complete', ['item_id' => $item->id]),
            ['payment_method' => 'カード払い']
        );

        $response->assertRedirect('https://example.test/checkout');

        $this->assertDatabaseHas('sold_items', [
            'item_id'  => $item->id,
            'buyer_id' => $buyer->id,
        ]);

    }

    // 購入された商品がトップページで売り切れ表示されること
    public function test_purchased_item_is_shown_as_sold_on_top_page()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        $seller = \App\Models\User::factory()->create();
        $buyer  = \App\Models\User::factory()->create();

        $buyer->profile()->create([
            'post_code' => '123-4567',
            'address' => 'テスト住所',
            'building_name' => 'テストビル',
        ]);
        $condition = \App\Models\Condition::first();

        $item = \App\Models\Item::factory()->create([
            'user_id'      => $seller->id,
            'condition_id' => $condition->id,
            'name'         => '購入される商品',
        ]);

        \Mockery::mock('alias:Stripe\Checkout\Session')
            ->shouldReceive('create')
            ->once()
            ->andReturn((object)['url' => 'https://example.test/checkout']);

        $this->actingAs($buyer)->post(
            route('purchase.complete', ['item_id' => $item->id]),
            ['payment_method' => 'カード払い']
        );

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSeeText($item->name);

        $response->assertSee('images/soldout.png');
    }


    // 購入した商品がマイページの購入した商品に表示されること
    public function test_purchased_item_is_shown_in_profile_purchase_list()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        $seller = \App\Models\User::factory()->create();
        $buyer  = \App\Models\User::factory()->create();

        $buyer->profile()->create([
            'post_code' => '123-4567',
            'address' => 'テスト住所',
        'building_name' => 'テストビル',
        ]);

        $condition = \App\Models\Condition::first();
        $item = \App\Models\Item::factory()->create([
            'user_id'      => $seller->id,
            'condition_id' => $condition->id,
            'name'         => '購入した商品',
        ]);

        \Mockery::mock('alias:Stripe\Checkout\Session')
            ->shouldReceive('create')
            ->once()
            ->andReturn((object)['url' => 'https://example.test/checkout']);

        $this->actingAs($buyer)->post(
            route('purchase.complete', ['item_id' => $item->id]),
            ['payment_method' => 'カード払い']
        );

        $response = $this->actingAs($buyer)->get('/mypage?page=buy');
        $response->assertStatus(200);

        $response->assertSeeText($item->name);
    }

    // 支払い方法でカード払いを選択した場合、Stripeのpayment_method_typesがcardになること
    public function test_payment_method_card_sets_stripe_payment_method_types_to_card()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        $seller = \App\Models\User::factory()->create();
        $buyer  = \App\Models\User::factory()->create();

        $buyer->profile()->create([
            'post_code' => '123-4567',
            'address' => 'テスト住所',
            'building_name' => 'テストビル',
        ]);

        $condition = \App\Models\Condition::first();
        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
        ]);

        \Mockery::mock('alias:Stripe\Checkout\Session')
            ->shouldReceive('create')
            ->once()
            ->with(\Mockery::on(function ($payload) {
                return isset($payload['payment_method_types'])
                    && $payload['payment_method_types'] === ['card'];
            }))
            ->andReturn((object)['url' => 'https://example.test/checkout']);

        $response = $this->actingAs($buyer)->post(
            route('purchase.complete', ['item_id' => $item->id]),
            ['payment_method' => 'カード払い']
        );

        $response->assertRedirect('https://example.test/checkout');
}

    // 支払い方法でコンビニ支払いを選択した場合、Stripeのpayment_method_typesがkonbiniになること
    public function test_payment_method_konbini_sets_stripe_payment_method_types_to_konbini()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        $seller = \App\Models\User::factory()->create();
        $buyer  = \App\Models\User::factory()->create();

        $buyer->profile()->create([
            'post_code' => '123-4567',
            'address' => 'テスト住所',
            'building_name' => 'テストビル',
        ]);

        $condition = \App\Models\Condition::first();
        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
        ]);

        \Mockery::mock('alias:Stripe\Checkout\Session')
            ->shouldReceive('create')
            ->once()
            ->with(\Mockery::on(function ($payload) {
                return isset($payload['payment_method_types'])
                    && $payload['payment_method_types'] === ['konbini'];
            }))
            ->andReturn((object)['url' => 'https://example.test/checkout']);

        $response = $this->actingAs($buyer)->post(
            route('purchase.complete', ['item_id' => $item->id]),
            ['payment_method' => 'コンビニ払い']
        );

        $response->assertRedirect('https://example.test/checkout');
    }

    // 住所変更画面で住所を更新すると、購入画面にも反映されること
    public function test_shipping_address_updated_on_address_page_is_reflected_on_purchase_page()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        $seller = \App\Models\User::factory()->create();
        $buyer  = \App\Models\User::factory()->create();

        $buyer->profile()->create([
            'post_code' => '111-1111',
            'address' => '旧住所',
            'building_name' => '旧ビル',
        ]);

        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => \App\Models\Condition::first()->id,
        ]);

        $response = $this->actingAs($buyer)->post(
            route('purchase.address.update', ['item_id' => $item->id]),
            [
                'post_code' => '123-4567',
                'address' => '新住所',
                'building_name' => '新ビル',
            ]
        );

        $response->assertRedirect(route('purchase.show', ['item_id' => $item->id]));

        $this->assertDatabaseHas('profiles', [
            'user_id' => $buyer->id,
            'post_code' => '123-4567',
            'address' => '新住所',
            'building_name' => '新ビル',
        ]);

        $page = $this->actingAs($buyer)->get(route('purchase.show', ['item_id' => $item->id]));
        $page->assertStatus(200);
        $page->assertSeeText('123-4567');
        $page->assertSeeText('新住所');
        $page->assertSeeText('新ビル');
    }

    //購入した商品に送付先住所が登録されること
    public function test_shipping_address_is_saved_to_sold_item_when_purchase_is_completed()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);

        $seller = \App\Models\User::factory()->create();
        $buyer  = \App\Models\User::factory()->create();

        $buyer->profile()->create([
            'post_code' => '999-9999',
            'address' => '配送先住所',
            'building_name' => '配送ビル',
        ]);

        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => \App\Models\Condition::first()->id,
        ]);

        \Mockery::mock('alias:Stripe\Checkout\Session')
            ->shouldReceive('create')
            ->once()
            ->andReturn((object)['url' => 'https://example.test/checkout']);

        $response = $this->actingAs($buyer)->post(
            route('purchase.complete', ['item_id' => $item->id]),
            ['payment_method' => 'カード払い']
        );

        $response->assertRedirect('https://example.test/checkout');

        $this->assertDatabaseHas('sold_items', [
            'item_id' => $item->id,
            'buyer_id' => $buyer->id,
            'post_code' => '999-9999',
            'address' => '配送先住所',
            'building_name' => '配送ビル',
        ]);
    }

}