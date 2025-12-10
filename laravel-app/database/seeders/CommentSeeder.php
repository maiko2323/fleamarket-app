<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->insert([
            [
                'item_id' => 1,
                'user_id' => 1,
                'content' => 'とても良い商品ですね！',
            ],
            [
                'item_id' => 1,
                'user_id' => 2,
                'content' => '購入を検討しています。',
            ],
            [
                'item_id' => 2,
                'user_id' => 3,
                'content' => '写真より少し色が違いますか？',
            ],
        ]);

    }
}
