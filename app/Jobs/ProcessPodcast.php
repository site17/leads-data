<?php

namespace App\Jobs;

use PDO;
use PDOException;
use Carbon\Carbon;
use App\Models\Lead;
use App\Models\OAuthToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessPodcast implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $this->process();
        } catch (\Throwable $e) {
            // log
            return;
        }

        Contact::despach();
    }

    protected function process()
    {
        // Подключение к базе через PDO
        //     $dsn = 'mysql:host=localhost;dbname=sim2_b;charset=utf8';
        //     $username = env('DB_USERNAME');
        //     $password = env('DB_PASSWORD');

        //     try {
        //         $pdo = new PDO($dsn, $username, $password, [
        //             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        //             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        //             PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false,
        //         ]);
        //     } catch (PDOException $e) {
        //         die('Ошибка подключения к базе данных: ' . $e->getMessage());
        //     }

        //     $tokenData = OAuthToken::where('service', 'amocrm')->first();

        //     if (!$tokenData) {
        //         // $this->error('Token not found');
        //         return 1;  // Возврат ошибки
        //     }

        //     $lastUpdateTimestamp = Lead::getLastUpdate();
        //     // $this->info(\sprintf('изначальная дата %s', strval($lastUpdateTimestamp)));

        //     $lastUpdateTimestamp = $lastUpdateTimestamp->subHours(6);
        //     // $this->info(\sprintf('старт %s', strval($lastUpdateTimestamp)));

        //     $lastUpdateTimestamp = $lastUpdateTimestamp->timestamp;
        //     $currentTimestamp    = now()->addDay()->timestamp;

        //     $url = "https://{$tokenData->subdomain}.amocrm.ru/api/v4/leads?filter[updated_at][from]=$lastUpdateTimestamp&filter[updated_at][to]=$currentTimestamp&limit=200";

        //     set_time_limit(0);
        //     $i = 0;

        //     // Подготовленный запрос для вставки данных
        //     $stmt = $pdo->prepare("START TRANSACTION;
        //     INSERT INTO leads (lead_id, name, status_id, pipeline_id, responsible_user_id, price, created_by, updated_by, closed_at, is_deleted, raw_content, created_at_amo, updated_at_amo, account_id, group_id)
        //     VALUES (:lead_id, :name, :status_id, :pipeline_id, :responsible_user_id, :price, :created_by, :updated_by, :closed_at, :is_deleted, :raw_content, :created_at_amo, :updated_at_amo, :account_id, :group_id)
        //     ON DUPLICATE KEY UPDATE
        //         name = VALUES(name),
        //         status_id = VALUES(status_id),
        //         pipeline_id = VALUES(pipeline_id),
        //         responsible_user_id = VALUES(responsible_user_id),
        //         price = VALUES(price),
        //         created_by = VALUES(created_by),
        //         updated_by = VALUES(updated_by),
        //         closed_at = VALUES(closed_at),
        //         is_deleted = VALUES(is_deleted),
        //         raw_content = VALUES(raw_content),
        //         created_at_amo = VALUES(created_at_amo),
        //         updated_at_amo = VALUES(updated_at_amo),
        //         account_id = VALUES(account_id),
        //         group_id = VALUES(group_id);COMMIT;
        // ");

        //     while (true) {
        //         gc_collect_cycles();

        //         $response = Http::withToken($tokenData->access_token)->get($url);
        //         if ($response->successful()) {
        //             $json = $response->json();
        //             $leads = $json['_embedded']['leads'];

        //             // $this->info(\sprintf('Лиды %s', sizeof($leads)));
        //             // $this->info(\sprintf('Итерация %s', $i));
        //             // $this->info(\sprintf('память %s', \memory_get_usage()));

        //             foreach ($leads as $lead) {
        //                 // Приводим данные к нужному формату и вставляем через PDO
        //                 $stmt->execute([
        //                     ':lead_id' => $lead['id'],
        //                     ':name' => $lead['name'] ?? null,
        //                     ':status_id' => $lead['status_id'] ?? null,
        //                     ':pipeline_id' => $lead['pipeline_id'] ?? null,
        //                     ':responsible_user_id' => $lead['responsible_user_id'] ?? null,
        //                     ':price' => $lead['price'] ?? 0,
        //                     ':created_by' => $lead['created_by'] ?? null,
        //                     ':updated_by' => $lead['updated_by'] ?? null,
        //                     ':closed_at' => isset($lead['closed_at']) ? Carbon::createFromTimestamp($lead['closed_at']) : null,
        //                     ':is_deleted' => $lead['is_deleted'] ?? false,
        //                     ':raw_content' => \json_encode($lead),
        //                     ':created_at_amo' => isset($lead['created_at']) ? Carbon::parse($lead['created_at']) : null,
        //                     ':updated_at_amo' => isset($lead['updated_at']) ? Carbon::parse($lead['updated_at']) : null,
        //                     ':account_id' => $lead['account_id'] ?? null,
        //                     ':group_id' => $lead['group_id'] ?? null,
        //                 ]);
        //                 $stmt->closeCursor();
        //             }
        //             // $this->info(
        //             //     \sprintf('память 2 %s', \memory_get_usage())
        //             // );
        //             $i++;

        //             // Переходим на следующую страницу
        //             $next = $json['_links']['next']['href'] ?? null;
        //             if (!$next) {
        //                 break;
        //             }
        //             $url = $next;
        //         } else {
        //             // $this->error('Failed to fetch leads');
        //             return 1;  // Возврат ошибки
        //         }
        //     }

        //     // $this->error('Leads successfully synced(updated)');
        //     return 0;
        file_put_contents(mt_rand(100, 999) . "_tmp.txt", 1);
    }
}
