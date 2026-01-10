<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\User;


class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Item::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(2, true),
            'brand' => $this->faker->word(),
            'description' => $this->faker->sentence(8),
            'price' => $this->faker->numberBetween(500,3000),
            'condition_id' => 1,
            'item_img' => 'images/sample.png',
        ];
    }
}
