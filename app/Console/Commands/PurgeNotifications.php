<?php

namespace App\Console\Commands;

class PurgeNotifications extends Command
{
    /**
     * Minimum number of days.
     *
     * @const int
     */
    const MIN_DAYS = 7;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:purge {--d|days=7 : Older than this number of days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete read notifications older than a number of days';

    /**
     * Execute the console command.
     *
     * @param  \App\Repositories\Contracts\NotificationRepository $nofificationRepository
     * @return mixed
     */
    public function handle(\App\Repositories\Contracts\NotificationRepository $nofificationRepository)
    {
        // Check number of days
        $days = (int) $this->option('days');
        if ($days < static::MIN_DAYS) {
            return $this->error('The minum number of days is ' . static::MIN_DAYS);
        }

        // Delete notifications
        $date = now()->subDays($days);
        $this->comment('Deleting read notifications older than ' . $date);
        $deleted = $nofificationRepository->deleteReadOlderThan($date);

        $this->info("$deleted notifications deleted");
    }
}
