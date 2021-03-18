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
        Commands\VentilatorValueBatch::class
    ];

    /**
     * Define the application's command schedule.
     * local
     * crontab -e * * * * * cd /mnt/workspace/web/ &&php artisan schedule:run --env=local >> /dev/null 2>&1
     * dev
     * crontab -e * * * * * cd /var/www/3d_ventilator_web/ &&php artisan schedule:run --env=dev >> /dev/null 2>&1
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('set_fixed_flg')->cron('0 */'.config('system.fixed_flg_interval').' * * *');
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
