<?php namespace App\Console;

use App\Console\Commands\ApiDocGenerator;
use App\Console\Commands\AssetsBuilder;
use App\Console\Commands\AssetsCleaner;
use App\Console\Commands\AssetsUpdate;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AssetsCleaner::class,
        ApiDocGenerator::class,
        AssetsUpdate::class,
        AssetsBuilder::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
