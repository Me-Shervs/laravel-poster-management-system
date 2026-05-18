<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poster;

class CleanupDraftsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posters:cleanup-drafts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old draft posters older than 90 days';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Poster::query()
            ->where('status', 'draft')
            ->where(
                'created_at',
                '<=',
                now()->subDays(90)
            )
            ->chunkById(100, function ($posters) {

                foreach ($posters as $poster) {
                    $poster->delete();
                }
            });

        $this->info('Old draft posters cleaned successfully.');
    }
}