<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Lead;
use App\Models\OAuthToken;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class getLeadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-leads-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получаем все лиды';

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

        // 2142 leads_pipeline_id_foreign
        $url = "https://{$tokenData->subdomain}.amocrm.ru/api/v4/leads?limit=50&page=3879";
        set_time_limit(0);
        while (true) {

            $ms = microtime(true);
            $response = Http::withToken($tokenData->access_token)
                ->get($url);
            $ms2 = microtime(true) - $ms;
            $this->info('$ms2 ' . (string)$ms2);
            if ($response->successful()) {
                $json = $response->json();
                $leads = $json['_embedded']['leads'];
                $ms = microtime(true);

                DB::beginTransaction(); // Начало транзакции

                $records = [];
                foreach ($leads as $lead) {

                    $records[] = [
                        'lead_id' => $lead['id'],
                        'name' => $lead['name'] ?? null,
                        'status_id' => $lead['status_id'] ?? null,
                        'pipeline_id' => $lead['pipeline_id'] ?? null,
                        'responsible_user_id' => $lead['responsible_user_id'] ?? null,
                        'price' => $lead['price'] ?? 0,
                        'created_by' => $lead['created_by'] ?? null,
                        'updated_by' => $lead['updated_by'] ?? null,
                        'closed_at' => isset($lead['closed_at']) ? Carbon::createFromTimestamp($lead['closed_at']) : null,
                        'is_deleted' => $lead['is_deleted'] ?? false,
                        'raw_content' => \json_encode($lead), // Сохраняем весь ответ для дальнейшего анализа
                        'created_at_amo' => isset($lead['created_at']) ? Carbon::parse($lead['created_at']) : null,
                        'updated_at_amo' => isset($lead['updated_at']) ? Carbon::parse($lead['updated_at']) : null,
                        'account_id' => $lead['account_id'] ?? null,
                        'group_id' => $lead['group_id'] ?? null,
                    ];
                }

                DB::table('leads')->upsert($records, 'lead_id', [
                    'name',
                    'status_id',
                    'pipeline_id',
                    'responsible_user_id',
                    'price',
                    'created_by',
                    'updated_by',
                    'closed_at',
                    'is_deleted',
                    'raw_content',
                    'created_at_amo',
                    'updated_at_amo',
                    'account_id',
                    'group_id',
                ]);

                DB::commit(); // Подтверждение транзакции


                $ms3 = microtime(true) - $ms;
                $this->info('$ms3 ' . (string)$ms3);
                Log::info('leads', [
                    'url'      => $url,
                    'lead' => \sizeof($leads),
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
                $this->info('Failed to fetch leads');
                return 1;
            }
        }
        $this->info('Leads successfully synced');
        return 0;  // Успешное выполнение

    }
}
