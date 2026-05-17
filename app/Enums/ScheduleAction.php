<?php

namespace App\Enums;

enum ScheduleAction: string
{
    case Publish = 'publish';
    case Unpublish = 'unpublish';
}