<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\updateFilteringLeads;
use Illuminate\Support\Facades\Log;

class startAmoCrm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start-amo-crm';

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
        // создание задачи для получения лидов
        if (true) {

            updateFilteringLeads::dispatch();
        }
    }
}
