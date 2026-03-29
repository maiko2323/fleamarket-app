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
            'buyer_id' => 3,
            'payment_method' => 'カード払い',
            'post_code' => '530-0001',
            'address' => '大阪府大阪市北区梅田1-1-1',
            'building_name' => 'グランフロント大阪タワーA 12F',
            'sold_at' => now(),
        ]);

    }
}
