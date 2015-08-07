<?php

namespace App\Console\Commands;

use App\Models\Service;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ApiDocGenerator extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apidoc:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Api doc';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (\App::environment() === 'production') {
            $this->error('This feature is no available on this server');
        }

        $process = new Process('apidoc -i ' . base_path('app/Http/Controllers') . ' -o ' . base_path('../doc'));

        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Impossible to generate doc');
            $this->error($process->getErrorOutput());
            $this->info('You need to install apidoc: (sudo) npm install apidoc -g');

            return;
        }

        $this->comment('Documentation generated in folder ' . base_path('../doc'));
    }
}
