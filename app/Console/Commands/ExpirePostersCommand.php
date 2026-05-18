<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poster;

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
        Poster::query()
            ->where('status', 'published')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update([
                'status' => 'expired',
            ]);

        $this->info('Expired posters updated successfully.');
    }
}