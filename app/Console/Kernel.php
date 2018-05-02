<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Project;
use App\Jobs\PingWebsite;

class Kernel extends ConsoleKernel
{
    /**
    * The Artisan commands provided by your application.
    *
    * @var array
    */
    protected $commands = [
        //
    ];

    /**
    * Define the application's command schedule.
    *
    * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
    * @return void
    */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('monitor:check-uptime')->everyMinute();
        $schedule->command('monitor:check-certificate')->daily();

        $projects = Project::all();

        foreach ($projects as $key => $project) {
            if(!empty($url = $project->profile()->url)){
                $schedule->job(new PingWebsite($project))->cron('0/5 * * * *')->withoutOverlapping();
            }
        }
    }

    /**
    * Register the commands for the application.
    *
    * @return void
    */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
