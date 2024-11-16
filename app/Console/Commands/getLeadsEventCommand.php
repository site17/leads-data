<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\OAuthToken;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class getLeadsEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-leads-event-command';

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


        $tokenData = OAuthToken::where('service', 'amocrm')->first();

        if (!$tokenData) {
            $this->error('Token not found');
            return 1;  // Возврат ошибки
        }


        $url = "https://{$tokenData->subdomain}.amocrm.ru/api/v4/events?limit=50";
        set_time_limit(0);
        while (true) {

            $ms = microtime(true);
            $response = Http::withToken($tokenData->access_token)
                ->get($url);
            $ms2 = microtime(true) - $ms;
            $this->info('$ms2 ' . (string)$ms2);
            if ($response->successful()) {
                $json = $response->json();
                $events = $json['_embedded']['events'];
                $ms = microtime(true);

                DB::beginTransaction(); // Начало транзакции

                $records = [];
                foreach ($events as $event) {
                    $records[] = [
                        'event_id' => $event['id'],
                        'type' => $event['type'] ?? null,
                        'entity_id' => $event['entity_id'] ?? null,
                        'lead_pid' => null,
                        'entity_type' => $event['entity_type'] ?? null,
                        'created_by' => $event['created_by'] ?? null,
                        'created_at_amo' => isset($event['created_at']) ? Carbon::parse($event['created_at']) : null,
                        'value_after' => \json_encode($event['value_after']),
                        'value_before' => \json_encode($event['value_before']),
                        'account_id' => $event['account_id'] ?? null,
                        'raw' => \json_encode($event),
                        // 'created_at' => now(),  // Устанавливаем вручную время создания
                        // 'updated_at' => now()   // Устанавливаем вручную время обновления
                    ];
                }

                DB::table('amocrm_events')->upsert($records, 'event_id', [
                    'event_id',
                    'type',
                    'entity_id',
                    'lead_pid',
                    'entity_type',
                    'created_by',
                    'created_at_amo',
                    'value_after',
                    'value_before',
                    'account_id',
                    'raw',
                ]);

                DB::commit(); // Подтверждение транзакции


                $ms3 = microtime(true) - $ms;
                $this->info('$ms3 ' . (string)$ms3);
                Log::info('events', [
                    'url'      => $url,
                    'event' => \sizeof($events),
                    '_links'   => $json['_links'],
                    '$ms2' => $ms2,
                    '$ms3' => $ms3,
                ]);
                $next = $json['_links']['next']['href'];
                if (!isset($next) || $next === '') {
                    break;
                }
                $url = $json['_links']['next']['href'];
            } else {
                $this->info('Failed to fetch events');
                return 1;
            }
        }
        $this->info('events successfully synced');
        return 0;  // Успешное выполнение

    }
}
