<?php

namespace App\Console\Commands;

use App\Models\Service;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class AssetsCleaner extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove build assets';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info('Assets::Clean cleaning build assets');

        $this->info('Delete public/assets/css');
        $this->deleteDir(base_path('public/assets/css'));

        $this->info('Delete public/assets/js');
        $this->deleteDir(base_path('public/assets/js'));

        $this->info('Delete storage/tmp');
        $this->deleteDir(storage_path('tmp'));


        if($this->confirm('Delete public/assets/img folder ?', false)) {
            $this->deleteDir(base_path('public/assets/img'));
        }

        if($this->confirm('Delete public/assets/font folder ?', false)) {
            $this->deleteDir(base_path('public/assets/font'));
        }
    }

    public function deleteDir ($dirPath)
    {
        if (!is_dir($dirPath)) {
            $this->error($dirPath . ' is not a folder');
            return;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}
