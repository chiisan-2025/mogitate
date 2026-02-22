<?php

namespace Database\Factories;

use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConditionFactory extends Factory
{
    protected $model = Condition::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                '良好',
                '目立った傷や汚れなし',
                '傷や汚れあり',
            ]),
        ];
    }
}