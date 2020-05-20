<?php

namespace App\Console;

use App\Console\Commands\syncV2Database;
use App\Console\Commands\DeleteDraftPlaylistsPlans;
use App\Console\Commands\DeleteTemporaryZipFiles;
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

        Commands\syncV2Database::class,
        Commands\syncV2Users::class,
        Commands\syncV2Profiles::class,
        Commands\syncV2Bookmarks::class,
        Commands\syncV2Highlights::class,
        Commands\syncV2Notes::class,
        Commands\reSyncV2Notes::class,
        Commands\encryptNote::class,

        Commands\syncPlaylistDuration::class,
        Commands\DeleteDraftPlaylistsPlans::class,
        Commands\DeleteTemporaryZipFiles::class,

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
        $schedule->command(syncV2Database::class)
            ->environments(['prod'])  /* no need to do on dev, and reduces load on v2 db */
            ->everyFifteenMinutes()
            ->onOneServer()
            ->appendOutputTo('/var/app/current/storage/logs/artisan-scheduler.log')
            ->withoutOverlapping();

        $schedule->command(DeleteDraftPlaylistsPlans::class)
            ->hourlyAt(7)  /* pick a distinct time to aid debugging if needed */
            ->onOneServer()
            ->withoutOverlapping();

        $schedule->command(DeleteTemporaryZipFiles::class)
            ->hourlyAt(37) /* pick a distinct time to aid debugging if needed */
            ->withoutOverlapping();
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
