<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('team_id')->constrained()->onDelete('cascade'); // tarefa pertence a equipe
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->onDelete('set null'); // usuário responsável
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
