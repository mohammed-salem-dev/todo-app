<?php

namespace App\Enums;

use Carbon\Carbon;

enum RecurrenceType: string
{
    case Daily   = 'daily';
    case Weekly  = 'weekly';
    case Monthly = 'monthly';

    public function label(): string
    {
        return match($this) {
            self::Daily   => 'Daily',
            self::Weekly  => 'Weekly',
            self::Monthly => 'Monthly',
        };
    }

    /** Given a base date, return the next due date for this recurrence */
    public function nextDueDate(Carbon $from): Carbon
    {
        return match($this) {
            self::Daily   => $from->copy()->addDay(),
            self::Weekly  => $from->copy()->addWeek(),
            self::Monthly => $from->copy()->addMonth(),
        };
    }
}
