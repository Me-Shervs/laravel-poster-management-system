<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poster;
use App\Models\Schedule;
use App\Enums\PosterStatus;
use App\Enums\ScheduleStatus;

class WeeklyPosterCommand extends Command
{
    protected $signature = 'posters:report';

    protected $description = 'Generate weekly poster statistics report';

    public function handle(): void
    {
        $summary = [
            'posters' => [
                'draft' => Poster::where('status', PosterStatus::Draft->value)->count(),
                'scheduled' => Poster::where('status', PosterStatus::Scheduled->value)->count(),
                'published' => Poster::where('status', PosterStatus::Published->value)->count(),
                'expired' => Poster::where('status', PosterStatus::Expired->value)->count(),
            ],

            'schedules' => [
                'pending' => Schedule::where('status', ScheduleStatus::Pending->value)->count(),
                'processed' => Schedule::where('status', ScheduleStatus::Processed->value)->count(),
                'failed' => Schedule::where('status', ScheduleStatus::Failed->value)->count(),
            ],

            'weekly_activity' => [
                'new_posters' => Poster::where('created_at', '>=', now()->subWeek())->count(),
                'new_schedules' => Schedule::where('created_at', '>=', now()->subWeek())->count(),
            ],
        ];

        logger()->info('Weekly Poster Report', $summary);

        $this->table(
            ['Metric', 'Count'],
            [
                ['Draft', $summary['posters']['draft']],
                ['Scheduled', $summary['posters']['scheduled']],
                ['Published', $summary['posters']['published']],
                ['Expired', $summary['posters']['expired']],
            ]
        );
    }
}