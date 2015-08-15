<?php

namespace App\Console\Commands;

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use App\Libraries\Assets\Orchestrator;
use App\Models\Service;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class AssetsBuilder extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build assets groups';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Clean assets');
        \Artisan::call('assets:clean');

        if(config('assets.concat')) {
            $this->info('Build with concatenation');
        } else {
            $this->info('Build without concatenation');
        }

        $this->info("");

        \Log::info('Assets::Build all groups');

        /** @var Orchestrator $ocherstator */
        $ocherstator = \App::make('App\Libraries\Assets\Orchestrator');

        foreach(config('assets.groups') as $groupname => $assets)
        {
            $collection = \App\Libraries\Assets\Collection::createByGroup($groupname);

            $this->info("\t - Build " . $groupname);

            try {
                $buildTypes = $ocherstator->build($collection);

                if(count($buildTypes) === 0) {
                    $this->comment('No build');
                } else {
                    foreach ($buildTypes as $buildType) {
                        $this->comment("\t\t - " . $buildType . " build");
                    }
                }
            } catch (\Exception $e) {
                $this->error($e);
                return;
            }
        }

        $this->info("");
        $this->info('Build successful');

        $this->comment(\PHP_Timer::resourceUsage());
    }
}
