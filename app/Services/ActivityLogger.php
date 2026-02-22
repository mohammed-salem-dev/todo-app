<?php

namespace App\Services;

use App\Models\ActivityEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    public static function log(
        User   $actor,
        Model  $subject,
        string $action,
        ?int   $projectId = null,
        array  $meta = []
    ): void {
        ActivityEvent::create([
            'actor_id'     => $actor->id,
            'project_id'   => $projectId,
            'subject_type' => class_basename($subject),
            'subject_id'   => $subject->getKey(),
            'action'       => $action,
            'meta'         => empty($meta) ? null : $meta,
        ]);
    }

    /**
     * Build a human-readable sentence from an ActivityEvent.
     * Used in Blade — keeps all message logic out of views.
     */
    public static function describe(ActivityEvent $event): string
    {
        $actor   = $event->actor->name ?? 'Someone';
        $subject = strtolower($event->subject_type); // 'project' | 'task'
        $meta    = $event->meta ?? [];
        $name    = $meta['task_title'] ?? $meta['new_name'] ?? $meta['project_name'] ?? "#{$event->subject_id}";

        return match ($event->action) {
            'created'              => "{$actor} created {$subject} \"{$name}\"",
            'updated'              => self::describeUpdate($actor, $subject, $meta, $name),
            'deleted'              => "{$actor} deleted {$subject} \"{$name}\"",
            'state_changed'        => self::describeStateChange($actor, $meta, $name),
            'labels_updated'       => "{$actor} updated labels on \"{$name}\"",
            'recurrence_generated' => "{$actor} auto-created recurring task \"{$name}\"",
            default                => "{$actor} {$event->action} {$subject} \"{$name}\"",
        };
    }

    private static function describeUpdate(string $actor, string $subject, array $meta, string $name): string
    {
        if (isset($meta['state_from'], $meta['state_to'])) {
            return "{$actor} moved \"{$name}\" from {$meta['state_from']} → {$meta['state_to']}";
        }
        if (isset($meta['old_name'], $meta['new_name'])) {
            return "{$actor} renamed {$subject} \"{$meta['old_name']}\" → \"{$meta['new_name']}\"";
        }
        return "{$actor} updated {$subject} \"{$name}\"";
    }

    private static function describeStateChange(string $actor, array $meta, string $name): string
    {
        $from  = $meta['from'] ?? '?';
        $to    = $meta['to']   ?? '?';
        $title = $meta['task_title'] ?? $name;

        if ($to === 'done') {
            return "{$actor} completed \"{$title}\"";
        }
        if ($from === 'done') {
            return "{$actor} reopened \"{$title}\"";
        }
        return "{$actor} moved \"{$title}\" from {$from} → {$to}";
    }
}
