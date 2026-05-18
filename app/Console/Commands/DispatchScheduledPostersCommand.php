<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Jobs\PublishPosterJob;

class DispatchScheduledPostersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posters:dispatch-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch scheduled poster publishing jobs';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Schedule::query()
            ->where('status', 'pending')
            ->where('scheduled_at', '<=', now())
            ->each(function ($schedule) {

                PublishPosterJob::dispatch($schedule);
            });

        $this->info('Scheduled poster jobs dispatched successfully.');
    }
}