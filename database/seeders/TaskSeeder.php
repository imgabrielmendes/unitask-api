<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task\Task;
use App\Models\Team\Team;
use App\Models\User\User;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->get();
        if ($users->isEmpty()) {
            $users = User::factory()->count(10)->create();
        }

        $teams = Team::query()->with('users')->get();
        if ($teams->isEmpty()) {
            $teams = Team::factory()->count(5)->create();
        }

        foreach ($teams as $team) {
            if ($team->users()->count() === 0) {
                $team->users()->attach(
                    $users->random(min(3, $users->count()))->pluck('id')->all()
                );
                $team->load('users');
            }
        }

        $teams = Team::query()->with('users')->get();

        for ($i = 0; $i < 50; $i++) {
            $team = $teams->random();
            $assignedUserId = $team->users->random()->id;

            Task::factory()->create([
                'team_id' => $team->id,
                'assigned_user_id' => $assignedUserId,
            ]);
        }
    }
}
