<?php

namespace App\Models;

use App\Enums\RecurrenceType;
use App\Enums\TaskState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'title', 'details', 'state',
        'due_at', 'completed_at', 'sort_order',
        'recurrence_type', 'recurrence_interval',
    ];

    protected $casts = [
        'state'            => TaskState::class,
        'recurrence_type'  => RecurrenceType::class,
        'due_at'           => 'datetime',
        'completed_at'     => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopeWithState(Builder $query, TaskState $state): void
    {
        $query->where('state', $state->value);
    }

    public function scopeOverdue(Builder $query): void
    {
        $query->whereNotNull('due_at')
              ->where('due_at', '<', now())
              ->where('state', '!=', TaskState::Done->value);
    }

    public function scopeSearch(Builder $query, string $term): void
    {
        $query->where('title', 'like', "%{$term}%")
              ->orWhere('details', 'like', "%{$term}%");
    }

    public function scopeWithLabel(Builder $query, int $labelId): void
{
    $query->whereHas('labels', fn (Builder $q) => $q->where('labels.id', $labelId));
}

public function scopeDueBucket(Builder $query, string $bucket): void
{
    match ($bucket) {
        'overdue'        => $query->where('due_at', '<', now())
                                  ->where('state', '!=', TaskState::Done->value),
        'due_today'      => $query->whereDate('due_at', today()),
        'due_this_week'  => $query->whereBetween('due_at', [now(), now()->endOfWeek()]),
        default          => null,
    };
}


    // ── Helpers ────────────────────────────────────────────────

    public function isRecurring(): bool
    {
        return $this->recurrence_type !== null;
    }

    /**
 * Create the next occurrence of this recurring task.
 * Returns the new Task or null if not recurring.
 */
public function spawnNextOccurrence(): ?Task
{
    if (! $this->isRecurring()) {
        return null;
    }

    $base    = $this->due_at ?? $this->completed_at ?? now();
    $nextDue = $this->recurrence_type->nextDueDate($base);

    // Bump existing todo tasks' sort_order to make room at top
    Task::where('project_id', $this->project_id)
        ->where('state', TaskState::Todo->value)
        ->increment('sort_order');

    $next = Task::create([
        'project_id'          => $this->project_id,
        'title'               => $this->title,
        'details'             => $this->details,
        'state'               => TaskState::Todo->value,
        'due_at'              => $nextDue,
        'sort_order'          => 0,             // top of To Do
        'recurrence_type'     => $this->recurrence_type,
        'recurrence_interval' => $this->recurrence_interval,
        'completed_at'        => null,
    ]);

    // Copy labels
    $next->labels()->sync($this->labels->pluck('id'));

    return $next;
}

}
