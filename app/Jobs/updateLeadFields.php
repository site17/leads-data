<?php

namespace App\Jobs;

use PDO;
use PDOException;
use App\Models\OAuthToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class updateLeadFields implements ShouldQueue
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
    public function handle(): void
    {
        try {
            $this->process();
        } catch (\Throwable $e) {
            Log::error('Ошибка при обновлении lead fields: ' . $e->getMessage());
            return;
        }
        extractLeadFieldJob::dispatch();
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

        $url = "https://{$tokenData->subdomain}.amocrm.ru/api/v4/leads/custom_fields?limit=50";
        set_time_limit(0);
        gc_collect_cycles();
        $i = 0;
        try {
            while (true) {

                $response = Http::withToken($tokenData->access_token)
                    ->get($url);
                if ($response->failed()) {
                    Log::error('Ошибка в запросе к AmoCRM');
                    throw new \Exception('Ошибка в запросе к AmoCRM');
                }
                $json = $response->json();
                $fields = $json['_embedded']['custom_fields'];
                $pdo->beginTransaction();
                $stmt = $pdo->prepare("INSERT INTO lead_fields (custom_field_id,name,code,sort,type,entity_type,is_predefined,is_api_only,group_id,required_statuses,enums,raw,created_at,updated_at)
                    VALUES (:custom_field_id,:name,:code,:sort,:type,:entity_type,:is_predefined,:is_api_only,:group_id,:required_statuses,:enums,:raw,:created_at,:updated_at)
                    ON DUPLICATE KEY UPDATE
                        name=VALUES(name),
                        code=VALUES(code),
                        sort=VALUES(sort),
                        type=VALUES(type),
                        entity_type=VALUES(entity_type),
                        is_predefined=VALUES(is_predefined),
                        is_api_only=VALUES(is_api_only),
                        group_id=VALUES(group_id),
                        required_statuses=VALUES(required_statuses),
                        enums=VALUES(enums),
                        raw=VALUES(raw),
                        updated_at=VALUES(updated_at);");

                foreach ($fields as $field) {

                    $stmt->execute([
                        ':custom_field_id' => $field['id'],
                        ':name' => $field['name'],
                        ':code' => $field['code'],
                        ':sort' => $field['sort'],
                        ':type' => $field['type'],
                        ':entity_type' => $field['entity_type'],
                        ':is_predefined' => $field['is_predefined'],
                        ':is_api_only' => $field['is_api_only'],
                        ':group_id' => $field['group_id'],
                        ':required_statuses' => \json_encode($field['required_statuses']),
                        ':enums' => \json_encode($field['enums']),
                        ':raw' => \json_encode($field),
                        ':created_at' => now(),  // Устанавливаем вручную время создания
                        ':updated_at' => now()   // Устанавливаем вручную время обновления
                    ]);
                }
                if ($pdo->inTransaction()) {
                    $pdo->commit();  // Фиксация транзакции
                    // Log::info('Транзакция успешно завершена custom_fields');
                }
                $i++;

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
