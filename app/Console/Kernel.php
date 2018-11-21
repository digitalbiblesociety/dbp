<?php

namespace App\Console;

use Aws\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

        Commands\BibleEquivalents\CompareEbible::class,
        Commands\BibleEquivalents\SyncBebliaBible::class,
        Commands\BibleEquivalents\SyncDigitalBibleLibrary::class,
        Commands\BibleEquivalents\SyncEbible::class,
        Commands\BibleEquivalents\SyncFcbhApk::class,
        Commands\BibleEquivalents\SyncScriptureEarth::class,
        Commands\BibleEquivalents\UpdateBibleLinkOrganizations::class,

        Commands\Wiki\GenerateWorldFactbook::class,
        Commands\Wiki\SyncAlphabets::class,
        Commands\Wiki\SyncLanguageDescriptions::class,
        Commands\Wiki\UpdateOrganizationsDblStatus::class,

        Commands\S3LogBackup::class,
        Commands\SyncUsers::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('BackUpLogs')->cron('5 * * * *');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
