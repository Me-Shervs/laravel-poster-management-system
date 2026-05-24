<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\Poster;
use App\Enums\PosterStatus;
use App\Enums\ScheduleStatus;
use App\Enums\ScheduleAction;

class PosterPublisherService
{
    public function handle(Schedule $schedule): void
    {
        $poster = $schedule->poster;

        if (!$poster) {
            return;
        }

        // prevent double processing
        if ($schedule->status !== ScheduleStatus::Pending) {
            return;
        }

        $oldValues = $poster->toArray();

        match ($schedule->action) {
            ScheduleAction::Publish => $this->publish($poster),
            ScheduleAction::Unpublish => $this->unpublish($poster),
        };

        $schedule->update([
            'status' => ScheduleStatus::Processed,
            'processed_at' => now(),
        ]);

        $poster->auditLogs()->create([
            'user_id' => $poster->user_id,
            'event' => 'poster_schedule_processed',
            'old_values' => $oldValues,
            'new_values' => $poster->fresh()->toArray(),
        ]);
    }

    private function publish(Poster $poster): void
    {
        $poster->update([
            'status' => PosterStatus::Published->value,
            'published_at' => now(),
        ]);
    }

    private function unpublish(Poster $poster): void
    {
        $poster->update([
            'status' => PosterStatus::Expired->value,
        ]);
    }
}