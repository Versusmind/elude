<?php
namespace App\Console;

use App\Console\Commands\ApiDocGenerator;
use App\Console\Commands\ApiGenerator;
use App\Console\Commands\AssetsBuilder;
use App\Console\Commands\AssetsCleaner;
use App\Console\Commands\AssetsUpdate;
use App\Console\Commands\QaPhpComposerSecurity;
use App\Console\Commands\QaPhpcpd;
use App\Console\Commands\QaPhpCsFixer;
use App\Console\Commands\QaPhpmd;
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
        QaPhpcpd::class,
        QaPhpCsFixer::class,
        QaPhpmd::class,
        QaPhpComposerSecurity::class,
        AssetsCleaner::class,
        ApiDocGenerator::class,
        AssetsUpdate::class,
        AssetsBuilder::class,
        ApiGenerator::class,
        \Clockwork\Cli\Laravel\Command::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
