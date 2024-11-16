<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\updatePipelinesJob;

class startAmoCrmPipelineCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start-amo-crm-pipeline-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // создание задачи для получения users amo
        if (true) {
            updatePipelinesJob::dispatch();
        }
    }
}
