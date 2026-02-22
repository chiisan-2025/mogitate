<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'condition_id' => Condition::factory(),
            'name' => $this->faker->word(),
            'brand' => $this->faker->company(),
            'description' => $this->faker->realText(30),
            'price' => $this->faker->numberBetween(100, 10000),
            'image_path' => 'items/dummy.jpg', // 実ファイル不要
            'is_sold' => 0,
            'sold_at' => null,
        ];
    }

    public function sold(): static
    {
        return $this->state(fn () => [
            'is_sold' => 1,
            'sold_at' => now(),
        ]);
    }
}