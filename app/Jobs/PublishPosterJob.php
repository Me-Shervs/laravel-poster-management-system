<?php

namespace App\Jobs;

use Throwable;
use App\Models\Schedule;
use App\Models\AuditLog;
use App\Enums\PosterStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PublishPosterJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function __construct(
        public Schedule $schedule
    ) {}

    public function handle(): void
    {
        $poster = $this->schedule->poster;

        if (!$poster) {
            return;
        }

        $oldValues = $poster->toArray();

        if ($this->schedule->action === 'publish') {

            $poster->update([
                'status' => PosterStatus::Published,
                'published_at' => now(),
            ]);
        }

        if ($this->schedule->action === 'unpublish') {

            $poster->update([
                'status' => PosterStatus::Expired,
            ]);
        }

        $this->schedule->update([
            'status' => 'processed',
            'processed_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => $poster->user_id,
            'event' => 'poster_schedule_processed',

            'old_values' => $oldValues,

            'new_values' => $poster->fresh()->toArray(),

            'auditable_id' => $poster->id,
            'auditable_type' => $poster::class,
        ]);
    }

    public function failed(
        ?Throwable $exception
    ): void {

        $this->schedule->update([
            'status' => 'failed',
        ]);

        logger()->error(
            'PublishPosterJob failed',
            [
                'schedule_id' => $this->schedule->id,
                'error' => $exception?->getMessage(),
            ]
        );
    }
}