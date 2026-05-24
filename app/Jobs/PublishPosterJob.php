<?php

namespace App\Jobs;

use Throwable;
use App\Models\Schedule;
use App\Services\PosterPublisherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishPosterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function __construct(
        public Schedule $schedule
    ) {}

    public function handle(PosterPublisherService $service): void
    {
        $service->handle($this->schedule);
    }

    public function failed(Throwable $exception): void
    {
        $this->schedule->update([
            'status' => 'failed',
        ]);

        logger()->error('PublishPosterJob failed', [
            'schedule_id' => $this->schedule->id,
            'error' => $exception->getMessage(),
        ]);
    }
}