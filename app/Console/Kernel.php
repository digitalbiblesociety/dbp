<?php

namespace App\Console;

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

        Commands\BibleEquivalents\SyncBebliaBible::class,
        Commands\BibleEquivalents\SyncDigitalBibleLibrary::class,
        Commands\BibleEquivalents\SyncTalkingBibles::class,
        Commands\BibleEquivalents\SyncEbible::class,
        Commands\BibleEquivalents\SyncFcbhApk::class,
        Commands\BibleEquivalents\SyncScriptureEarth::class,
        Commands\BibleEquivalents\UpdateBibleLinkOrganizations::class,

        Commands\BibleFormats\FormatGetBible::class,
        Commands\BibleFormats\FormatRunberg::class,

        Commands\Wiki\GenerateWorldFactbook::class,
        Commands\Wiki\SyncAlphabets::class,
        Commands\Wiki\SyncLanguageDescriptions::class,
        Commands\Wiki\OrgDigitalBibleLibraryCompare::class,

        Commands\StudyFormats\fetchTyndalePeople::class,

        Commands\loaderPush::class,

        Commands\syncV2Users::class,
        Commands\syncV2Profiles::class,
        Commands\syncV2Bookmarks::class,
        Commands\syncV2Highlights::class,
        Commands\syncV2Notes::class,

        Commands\S3LogBackup::class,
        Commands\CleanAndImportKD::class,

        Commands\showEnvironment::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('syncV2Users')->hourly();
        $schedule->command('syncV2Profiles')->hourly();
        $schedule->command('syncV2Highlights')->hourly();
        $schedule->command('syncV2Notes')->hourly();
        $schedule->command('syncV2Bookmarks')->hourly();

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
