<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Team;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Verifica se há time existente, senão cria um
        $team = Team::first() ?? Team::factory()->create();

        Task::factory()
            ->count(10)
            ->create([
                'assigned_user_id' => 1,
                'team_id' => $team->id,
            ]);
    }
}
