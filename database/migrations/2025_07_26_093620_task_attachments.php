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
        Schema::create('task_attachments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('task_id')->constrained()->onDelete('cascade');
    $table->string('filename');      // nome do arquivo armazenado
    $table->string('filepath');      // caminho no storage
    $table->string('filetype')->nullable();  // tipo MIME
    $table->unsignedBigInteger('filesize')->nullable();
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
