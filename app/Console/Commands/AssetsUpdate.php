<?php

namespace App\Console\Commands;

use App\Models\Service;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class AssetsUpdate extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update bower assets';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info('Assets::Update Run bower update');

        $process = new Process('bower update');

        $process->run(function ($type, $buffer) {
            if ('err' === $type) {
                $this->error($buffer);
            } else {
                $this->info($buffer);
            }
        });

        if (!$process->isSuccessful()) {
            $this->error('Impossible to update bower');
            $this->error($process->getErrorOutput());

            return;
        }
    }
}
