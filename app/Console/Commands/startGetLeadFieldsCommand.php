<?php

namespace App\Console\Commands;

use App\Jobs\updateLeadFields;
use Illuminate\Console\Command;

class startGetLeadFieldsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start-get-lead-fields-command';

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
            updateLeadFields::dispatch();
        }
    }
}
