<?php

namespace LisaFehr\Gallery\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gallery:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Gallery resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Gallery Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'public']);
        //$this->callSilent('vendor:publish', ['--tag' => 'vue-components']);

        //$this->comment('Publishing Gallery View...');
        //$this->callSilent('vendor:publish', ['--tag' => 'vue-views']);

        $this->info('Gallery scaffolding installed successfully.');
    }
}
