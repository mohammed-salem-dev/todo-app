<?php

namespace App\Enums;

enum TaskState: string
{
    case Todo  = 'todo';
    case Doing = 'doing';
    case Done  = 'done';

    public function label(): string
    {
        return match($this) {
            self::Todo  => 'To Do',
            self::Doing => 'Doing',
            self::Done  => 'Done',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Todo  => 'slate',
            self::Doing => 'blue',
            self::Done  => 'emerald',
        };
    }
}
