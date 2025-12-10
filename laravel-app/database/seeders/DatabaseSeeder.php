<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    $this->call([
    UserSeeder::class,
    CategorySeeder::class,
    ConditionSeeder::class,
    ProfileSeeder::class,
    ItemSeeder::class,
    SoldItemSeeder::class,
    CommentSeeder::class,
    LikeSeeder::class,
    CategoryItemSeeder::class,



]);
    }
}
