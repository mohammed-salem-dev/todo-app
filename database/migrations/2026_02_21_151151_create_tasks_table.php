<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('details')->nullable();
            $table->string('state')->default('todo');        // cast to TaskState enum
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('recurrence_type')->nullable();   // cast to RecurrenceType enum
            $table->unsignedInteger('recurrence_interval')->nullable()->default(1);
            $table->timestamps();

            $table->index('project_id');
            $table->index('state');
            $table->index(['project_id', 'sort_order']); // kanban ordering
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
