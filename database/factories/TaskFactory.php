<?php

namespace Database\Factories;

use App\Models\Task\Task;
use App\Models\Team\Team;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->paragraph,
            'team_id' => Team::factory(),
            'assigned_user_id' => User::factory(),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'due_date' => $this->faker->optional()->date('Y-m-d'),
        ];
    }
}
