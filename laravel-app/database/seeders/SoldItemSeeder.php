<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SoldItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sold_items')->insert([
            'item_id' => 1,
            'buyer_id' => 1,
            'payment_method' => 'カード払い',
            'post_code' => '860-1111',
            'address' => '熊本県合志市1-1',
            'building_name' => '未来ビル202',
            'sold_at' => now(),
        ]);

    }
}
