<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('watch_seconds')->default(0);

            $table->timestamps();

            // One progress record per user per lesson
            $table->unique(['user_id', 'lesson_id']);
            $table->index(['user_id', 'lesson_id', 'completed_at']); // for completion checks
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
    }
};