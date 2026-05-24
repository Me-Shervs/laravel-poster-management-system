<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poster;

class CleanupDraftsCommand extends Command
{
    protected $signature = 'posters:cleanup-drafts';

    protected $description = 'Delete old draft posters older than 90 days';

    public function handle(): void
    {
        $count = 0;

        Poster::query()
            ->where('status', 'draft')
            ->where('created_at', '<=', now()->subDays(90))
            ->chunkById(100, function ($posters) use (&$count) {

                foreach ($posters as $poster) {

                    $poster->auditLogs()->create([
                        'user_id' => null,
                        'event' => 'poster_cleanup_deleted',
                        'old_values' => $poster->toArray(),
                        'new_values' => null,
                    ]);

                    $poster->delete();

                    $count++;
                }
            });

        $this->info("Old draft posters cleaned successfully. Deleted: {$count}");
    }
}