<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('posters:expire')
    ->everyFiveMinutes();

Schedule::command('posters:dispatch-scheduled')
    ->everyMinute();

Schedule::command('posters:cleanup-drafts')
    ->daily();

Schedule::command('posters:report')
    ->weekly();
