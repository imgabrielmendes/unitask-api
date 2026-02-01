<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Team;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Gerar slugs para times existentes
        Team::withoutEvents(function () {
            $teams = Team::whereNull('slug')->get();
            foreach ($teams as $team) {
                $slug = Str::slug($team->name);
                $originalSlug = $slug;
                $count = 1;
                
                while (Team::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
                
                $team->slug = $slug;
                $team->save();
            }
        });

        // Tornar o campo unique e not null
        Schema::table('teams', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
