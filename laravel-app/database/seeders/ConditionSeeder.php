<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('conditions')->insert([
        ['label' => '良好'],
        ['label' => '目立った傷や汚れなし'],
        ['label' => 'やや傷や汚れあり'],
        ['label' => '状態が悪い'],
    ]);
    }
}
