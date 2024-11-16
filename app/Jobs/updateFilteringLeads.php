<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
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
use App\Console\Commands\getFilteringContactsCommand;

class updateFilteringLeads implements ShouldQueue
{
    use Queueable;

    public $timeout = 1800;
    public $failOnTimeout = false;
    public $tries = 5;

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
    public function handle(): void
    {
        try {
            $this->process();
        } catch (\Throwable $e) {
            Log::error('Ошибка при обновлении лидов: ' . $e->getMessage());
            return;
        }
        // В updateFilteringLeads
        updateFilteringContacts::dispatch();
    }

    protected function process()
    {
        // Подключение к базе через PDO
        $dbase = env('DB_DATABASE');
        $dsn = "mysql:host=localhost;dbname=$dbase;charset=utf8";
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        try {
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false,
            ]);
        } catch (PDOException $e) {
            Log::error('Ошибка подключения к базе данных: ' . $e->getMessage());
            // die('Ошибка подключения к базе данных: ' . $e->getMessage());
        }

        $tokenData = OAuthToken::where('service', 'amocrm')->first();

        if (!$tokenData) {
            Log::error('Токен AmoCRM не найден');
            return 1;  // Возврат ошибки
        }

        $lastUpdateTimestamp = Lead::getLastUpdate();
        $lastUpdateTimestamp = $lastUpdateTimestamp->subHours(6);
        $lastUpdateTimestamp = $lastUpdateTimestamp->timestamp;
        $currentTimestamp    = now()->addDay()->timestamp;

        $url = "https://{$tokenData->subdomain}.amocrm.ru/api/v4/leads?filter[updated_at][from]=$lastUpdateTimestamp&filter[updated_at][to]=$currentTimestamp&limit=100";

        set_time_limit(0);
        gc_collect_cycles();
        $i = 0;

        try {
            while (true) {
                $response = Http::withToken($tokenData->access_token)->get($url);
                if ($response->failed()) {
                    Log::error('Ошибка в запросе к AmoCRM');
                    throw new \Exception('Ошибка в запросе к AmoCRM');
                }
                $json = $response->json();
                $leads = $json['_embedded']['leads'];
                $pdo->beginTransaction();

                $stmt = $pdo->prepare("INSERT INTO leads (lead_id, name, status_id, pipeline_id, responsible_user_id, price, created_by, updated_by, closed_at, is_deleted, raw_content, created_at_amo, updated_at_amo, account_id, group_id,created_at,updated_at)
                    VALUES (:lead_id, :name, :status_id, :pipeline_id, :responsible_user_id, :price, :created_by, :updated_by, :closed_at, :is_deleted, :raw_content, :created_at_amo, :updated_at_amo, :account_id, :group_id,:created_at,:updated_at)
                    ON DUPLICATE KEY UPDATE
                        name = VALUES(name),
                        status_id = VALUES(status_id),
                        pipeline_id = VALUES(pipeline_id),
                        responsible_user_id = VALUES(responsible_user_id),
                        price = VALUES(price),
                        created_by = VALUES(created_by),
                        updated_by = VALUES(updated_by),
                        closed_at = VALUES(closed_at),
                        is_deleted = VALUES(is_deleted),
                        raw_content = VALUES(raw_content),
                        updated_at_amo = VALUES(updated_at_amo),
                        account_id = VALUES(account_id),
                        group_id = VALUES(group_id),
                        updated_at=VALUES(updated_at);");

                foreach ($leads as $lead) {
                    // Приводим данные к нужному формату и вставляем через PDO
                    $stmt->execute([
                        ':lead_id' => $lead['id'],
                        ':name' => $lead['name'] ?? null,
                        ':status_id' => $lead['status_id'] ?? null,
                        ':pipeline_id' => $lead['pipeline_id'] ?? null,
                        ':responsible_user_id' => $lead['responsible_user_id'] ?? null,
                        ':price' => $lead['price'] ?? 0,
                        ':created_by' => $lead['created_by'] ?? null,
                        ':updated_by' => $lead['updated_by'] ?? null,
                        ':closed_at' => isset($lead['closed_at']) ? Carbon::createFromTimestamp($lead['closed_at']) : null,
                        ':is_deleted' => $lead['is_deleted'] ?? false,
                        ':raw_content' => \json_encode($lead),
                        ':created_at_amo' => isset($lead['created_at']) ? Carbon::parse($lead['created_at']) : null,
                        ':updated_at_amo' => isset($lead['updated_at']) ? Carbon::parse($lead['updated_at']) : null,
                        ':account_id' => $lead['account_id'] ?? null,
                        ':group_id' => $lead['group_id'] ?? null,
                        ':created_at' => now(),  // Устанавливаем вручную время создания
                        ':updated_at' => now()   // Устанавливаем вручную время обновления
                    ]);
                    // $stmt->closeCursor();
                }

                if ($pdo->inTransaction()) {
                    $pdo->commit();  // Фиксация транзакции
                    // Log::info('Транзакция успешно завершена leads');
                }

                $i++;

                // Переходим на следующую страницу
                $next = $json['_links']['next']['href'] ?? null;
                if (!$next) {
                    break;
                }
                $url = $next;
            }
        } catch (\Exception $e) {
            $pdo->rollBack();
            Log::error('Ошибка в транзакции: ' . $e->getMessage());
            throw $e;
        }
    }
}
