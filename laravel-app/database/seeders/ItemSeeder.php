<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            [
                'user_id' => 3,
                'name' => '腕時計',
                'item_img' =>'images/watch.jpg',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'brand' => 'Rolax',
                'condition_id' => 1,

            ],
            [
                'user_id' => 1,
                'name' => 'HDD',
                'item_img' => 'images/hdd.jpg',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'brand' => '西芝',
                'condition_id' => 2,
            ],
            [
                'user_id' => 5,
                'name' => '革靴',
                'item_img' => 'images/shoes.jpg',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'brand' => null,
                'condition_id' => 4,
            ],
            [
                'user_id' => 1,
                'name' => 'ノートPC',
                'item_img' => 'images/pc.jpg',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'brand' => null,
                'condition_id' => 1,
            ],
            [
                'user_id' => 3,
                'name' => 'タンブラー',
                'item_img' => 'images/tumbler.jpg',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'brand' => null,
                'condition_id' => 4,
            ],
            [
                'user_id' => 3,
                'name' => 'コーヒーミル',
                'item_img' => 'images/coffee_mill.jpg',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'brand' => 'Starbacks',
                'condition_id' => 1,
            ],
            [
                'user_id' => 2,
                'name' => 'ショルダーバッグ',
                'item_img' => 'images/bag.jpg',
                'price' => 4000,
                'description' => 'おしゃれなショルダーバッグ',
                'brand' => null,
                'condition_id' => 3,
            ],
            [
                'user_id' => 4,
                'name' => '玉ねぎ3束',
                'item_img' => 'images/onion.jpg',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'brand' => null,
                'condition_id' => 3,
            ],
            [
                'user_id' => 5,
                'name' => 'マイク',
                'item_img' => 'images/mic.jpg',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'brand' => null,
                'condition_id' => 2,
            ],
            [
                'user_id' => 2,
                'name' => 'メイクセット',
                'item_img' => 'images/makeup_set.jpg',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'brand' => null,
                'condition_id' => 2,
            ],
        ]);

    }
}
