<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poster;
use App\Enums\PosterStatus;

class ExpirePostersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posters:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire published posters whose expiration date has passed';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $count = 0;

        Poster::query()
            ->where('status', PosterStatus::Published->value)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->each(function ($poster) use (&$count) {

                $old = $poster->toArray();

                $poster->update([
                    'status' => PosterStatus::Expired->value,
                ]);

                $poster->auditLogs()->create([
                    'user_id' => $poster->user_id,
                    'event' => 'poster_expired',
                    'old_values' => $old,
                    'new_values' => $poster->fresh()->toArray(),
                ]);

                $count++;
            });

        $this->info("Expired posters processed: {$count}");
    }
}