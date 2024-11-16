<?php

namespace App\Console\Commands;

use App\Jobs\updateUsersAmoJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class startAmoCrmTwoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start-amo-crm-two-command';

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
            updateUsersAmoJob::dispatch();
        }
    }
}
