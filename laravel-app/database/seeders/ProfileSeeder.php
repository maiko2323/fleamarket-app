<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->insert([
            [
                'user_id' => 1,
                'profile_img' => 'images/men1.png',
                'post_code' => '861-1111',
                'address' => '熊本県合志市1-1',
                'building_name' => '未来ビル202',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'profile_img' => 'images/women1.png',
                'post_code' => '100-0001',
                'address' => '東京都千代田区千代田1-1',
                'building_name' => '皇居前マンション101',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'profile_img' => 'images/men2.png',
                'post_code' => '530-0001',
                'address' => '大阪府大阪市北区梅田1-1-1',
                'building_name' => 'グランフロント大阪タワーA 12F',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
