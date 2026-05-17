<?php

namespace App\Enums;

enum ScheduleStatus: string
{
    case Pending = 'pending';
    case Processed = 'processed';
    case Failed = 'failed';
}