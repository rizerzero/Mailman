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
        \App\Console\Commands\DispatchMessageJobs::class,
        \App\Console\Commands\FindCompletedCampaigns::class,
        \App\Console\Commands\CatchRunawayLists::class,
        \App\Console\Commands\Push2Queue::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // $schedule->command('runaways');
        $schedule->command('dispatch-messages')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('push2queue')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('backup:run')->daily();
        $schedule->command('backup:clean')->weekly();
        $schedule->command('finish-campaigns')->everyTenMinutes();
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
