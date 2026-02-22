<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('subject_type');         // 'project' | 'task'
            $table->unsignedBigInteger('subject_id');
            $table->string('action');               // 'created' | 'state_changed' | etc.
            $table->json('meta')->nullable();        // extra context e.g. old/new state
            $table->timestamp('created_at')->useCurrent(); // log: no updated_at

            $table->index('actor_id');
            $table->index('project_id');
            $table->index(['subject_type', 'subject_id']); // fetch logs for any subject
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_events');
    }
};
