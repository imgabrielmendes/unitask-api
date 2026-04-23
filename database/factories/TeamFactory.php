<?php

namespace Database\Factories;

use App\Models\Team\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->company();

        $attributes = [
            'name' => $name,
        ];

        if (Schema::hasColumn('teams', 'slug')) {
            $attributes['slug'] = Str::slug($name).'-'.$this->faker->unique()->numerify('###');
        }

        return $attributes;
    }
}
