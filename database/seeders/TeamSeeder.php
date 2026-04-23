<?php

namespace Database\Seeders;

use App\Models\Team\Team;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->get();

        if ($users->isEmpty()) {
            $users = User::factory()->count(10)->create();
        }

        $teams = Team::factory()->count(5)->create();

        foreach ($teams as $team) {
            $team->users()->attach(
                $users->random(min(4, $users->count()))->pluck('id')->all()
            );
        }
    }
}
