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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('poster_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamp('scheduled_at');

            $table->enum('action', [
                'publish',
                'unpublish'
            ]);

            $table->enum('status', [
                'pending',
                'processed',
                'failed'
            ])->default('pending');

            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            $table->index('scheduled_at');
            $table->index('status');
            $table->index(['status', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
