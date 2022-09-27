<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category =  Category::All()->random(); // uma categoria randomica
        return [
            'title' => $this->faker->text(30),
            'description' => $this->faker->text(60),
            'due-date' => $this->faker->dateTime(),
            // 'user_id' => 1,
            'category_id' => $category, // retorna uma categoria randomica
            'user_id' => $category->user_id, // retorna o usuário da categoria
        ];
    }
}
