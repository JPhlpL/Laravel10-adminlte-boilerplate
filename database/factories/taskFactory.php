<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\task>
 */
class taskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Task::class;


    public function definition(): array
    {
        return [
            //
            'title' => $this->faker->name(),
            'task_num' => $this->faker->name(),
            'task_desc' => $this->faker->sentence(),
            'task_status' => $this->faker->text,
            'task_user' => $this->faker->text,
        ];
    }
}
