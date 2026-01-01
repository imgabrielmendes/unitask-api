<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'team_id' => 1, // ou Team::factory()
            'assigned_user_id' => 1,
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'due_date' => $this->faker->date,
        ];
    }
}
