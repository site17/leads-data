<?php

namespace App\Jobs;

use PDO;
use PDOException;
use Carbon\Carbon;
use App\Models\Statuses;
use App\Models\OAuthToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class updatePipelinesJob implements ShouldQueue
{
    use Dispatchable;
    public $timeout = 1800;
    public $failOnTimeout = false;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        try {
            $this->process();
        } catch (\Throwable $e) {
            // $this->fail($e);
            Log::error('Ошибка при обновлении воронок: ' . $e->getMessage(), ['exception' => $e]);
        }
    }

    protected function process()
    {
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
            return;
        }

        $tokenData = OAuthToken::where('service', 'amocrm')->first();

        if (!$tokenData) {
            Log::error('Токен AmoCRM не найден');
            return;
        }

        $url = "https://{$tokenData->subdomain}.amocrm.ru/api/v4/leads/pipelines";

        try {
            $response = Http::withToken($tokenData->access_token)->get($url);

            if ($response->failed()) {
                Log::error('Ошибка в запросе к AmoCRM');
                throw new \Exception('Ошибка в запросе к AmoCRM');
            }

            $json = $response->json();
            $pipelines = $json['_embedded']['pipelines'];

            $pdo->beginTransaction();
            Log::info('Транзакция началась pipeline');

            $pipelineStmt = $pdo->prepare("INSERT INTO pipeline (pipeline_id, name, sort, account_id, is_unsorted_on, is_archive, is_main, raw,created_at,updated_at)
                VALUES (:pipeline_id, :name, :sort, :account_id, :is_unsorted_on, :is_archive, :is_main, :raw,:created_at,:updated_at)
                ON DUPLICATE KEY UPDATE
                    name=VALUES(name),
                    sort=VALUES(sort),
                    account_id=VALUES(account_id),
                    is_unsorted_on=VALUES(is_unsorted_on),
                    is_archive=VALUES(is_archive),
                    is_main=VALUES(is_main),
                    raw=VALUES(raw),
                    updated_at=VALUES(updated_at);
            ");



            foreach ($pipelines as $pipeline) {
                $pipelineStmt->execute([
                    ':pipeline_id' => $pipeline['id'],
                    ':name' => $pipeline['name'] ?? null,
                    ':sort' => $pipeline['sort'],
                    ':account_id' => $pipeline['account_id'],
                    ':is_unsorted_on' => $pipeline['is_unsorted_on'],
                    ':is_archive' => $pipeline['is_archive'],
                    ':is_main' => $pipeline['is_main'],
                    ':raw' => json_encode($pipeline),
                    ':created_at' => now(),  // Устанавливаем вручную время создания
                    ':updated_at' => now()   // Устанавливаем вручную время обновления
                ]);

                // $pipelineId = $pipeline['id'];

                // // Сохранение статусов для текущего pipeline
                // if (!empty($pipeline['_embedded']['statuses'])) {
                //     foreach ($pipeline['_embedded']['statuses'] as $status) {
                //         $statusStmt->execute([
                //             ':status_id' => $status['id'],
                //             ':pipeline_id' => $pipelineId,
                //             ':name' => $status['name'],
                //             ':sort' => $status['sort'],
                //             ':is_editable' => $status['is_editable'] ?? false,
                //             ':color' => $status['color'] ?? null,
                //             ':type' => $status['type'] ?? null,
                //             ':account_id' => $status['account_id'] ?? null,
                //             ':raw' => json_encode($status),
                //             ':created_at' => now(),
                //             ':updated_at' => now(),
                //         ]);
                //     }
                // }
            }



            if ($pdo->inTransaction()) {
                $pdo->commit();  // Фиксация транзакции
                Log::info('Транзакция успешно завершена pipeline');
            }

            $pdo->beginTransaction();
            Log::info('Транзакция началась statuses');


            // Подготовка запроса для вставки или обновления в таблице statuses
            $statusStmt = $pdo->prepare("INSERT INTO statuses (status_id, pipeline_id, name, sort, is_editable, color, type, account_id, raw, created_at, updated_at)
                VALUES (:status_id, :pipeline_id, :name, :sort, :is_editable, :color, :type, :account_id, :raw, :created_at, :updated_at)
                ON DUPLICATE KEY UPDATE
                    name=VALUES(name),
                    sort=VALUES(sort),
                    is_editable=VALUES(is_editable),
                    color=VALUES(color),
                    type=VALUES(type),
                    account_id=VALUES(account_id),
                    raw=VALUES(raw),
                    updated_at=VALUES(updated_at);
            ");


            foreach ($pipelines as $pipeline) {

                $pipelineId = $pipeline['id'];

                // Сохранение статусов для текущего pipeline
                if (!empty($pipeline['_embedded']['statuses'])) {
                    foreach ($pipeline['_embedded']['statuses'] as $status) {
                        $statusStmt->execute([
                            ':status_id' => $status['id'],
                            ':pipeline_id' => $pipelineId,
                            ':name' => $status['name'],
                            ':sort' => $status['sort'],
                            ':is_editable' => $status['is_editable'] ?? false,
                            ':color' => $status['color'] ?? null,
                            ':type' => $status['type'] ?? null,
                            ':account_id' => $status['account_id'] ?? null,
                            ':raw' => json_encode($status),
                            ':created_at' => now(),
                            ':updated_at' => now(),
                        ]);
                    }
                }
            }
            if ($pdo->inTransaction()) {
                $pdo->commit();  // Фиксация транзакции
                Log::info('Транзакция успешно завершена statuses');
            }
        } catch (\Exception $e) {
            $pdo->rollBack();
            Log::error('Ошибка в транзакции: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function syncStatuses(array $statuses, int $pipelinePid)
    {
        foreach ($statuses as $status) {
            Statuses::updateOrCreate(
                ['status_id' => $status['id']],
                [
                    'pipeline_pid' => $pipelinePid,
                    'name' => $status['name'],
                    'sort' => $status['sort'],
                    'is_editable' => $status['is_editable'] ?? false,
                    'color' => $status['color'] ?? null,
                    'type' => $status['type'] ?? null,
                    'account_id' => $status['account_id'] ?? null,
                    'raw' => $status,
                ]
            );
        }
    }
}
