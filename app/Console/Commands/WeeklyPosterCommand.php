<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poster;
use App\Models\Schedule;

class WeeklyPosterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posters:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate weekly poster statistics report';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $summary = [
            'published' => Poster::where(
                'status',
                'published'
            )->count(),

            'expired' => Poster::where(
                'status',
                'expired'
            )->count(),

            'scheduled' => Schedule::where(
                'status',
                'pending'
            )->count(),
        ];

        logger()->info(
            'Weekly Poster Report',
            $summary
        );

        $this->info('Weekly poster report generated.');
    }
}