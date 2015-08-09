<?php

namespace App\Console\Commands;

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
        \Log::info('Assets::Build all groups');
        $ocherstator = \App::make('App\Libraries\Assets\Orchestrator');
        foreach(config('assets.groups') as $groupname => $assets)
        {
            $this->info('Build ' . $groupname);
            $buildType = 'style';
            if(array_key_exists(\App\Libraries\Assets\Asset::JS, $assets)) {
                $buildType = 'javascript';
            } elseif(array_key_exists(\App\Libraries\Assets\Asset::IMG, $assets)) {
                $buildType = 'img';
            } elseif(array_key_exists(\App\Libraries\Assets\Asset::FONT, $assets)) {
                $buildType = 'font';
            }

            $this->comment("\t " . $buildType . " build");

            try {
                $ocherstator->{$buildType}(\App\Libraries\Assets\Collection::createByGroup($groupname));
            } catch (\Exception $e) {
                $this->error($e);
                return;
            }
        }

        $this->info("");
        $this->info('Build successful');
    }
}
