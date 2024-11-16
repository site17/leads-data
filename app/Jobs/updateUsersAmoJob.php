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

class updateUsersAmoJob implements ShouldQueue
{
    use Queueable;
    // public $timeout = 1800;
    public $failOnTimeout = false;

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
            Log::error('Ошибка при обновлении пользователей: ' . $e->getMessage(), ['exception' => $e]);
            return;
        }
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
            Log::error('Ошибка подключения к базе данных: ' . $e->getMessage(), ['exception' => $e]);
        }

        $tokenData = OAuthToken::where('service', 'amocrm')->first();

        if (!$tokenData) {
            Log::error('Токен AmoCRM не найден');
            return 1;  // Возврат ошибки
        }

        $url = "https://{$tokenData->subdomain}.amocrm.ru/api/v4/users";

        set_time_limit(0);
        gc_collect_cycles();

        try {
            $response = Http::withToken($tokenData->access_token)->get($url);
            if ($response->failed()) {
                Log::error('Ошибка в запросе к AmoCRM');
                throw new \Exception('Ошибка в запросе к AmoCRM');
            }
            $json = $response->json();
            $users = $json['_embedded']['users'];
            $pdo->beginTransaction();
            // Log::info('Транзакция началась user');
            // Подготовленный запрос для вставки данных
            $stmt = $pdo->prepare("INSERT INTO crm_user (user_id, name, email, rights, raw,created_at,updated_at)
                    VALUES (:user_id, :name, :email, :rights, :raw,:created_at,:updated_at)
                    ON DUPLICATE KEY UPDATE  
                        name=VALUES(name),
                        email=VALUES(email),
                        rights=VALUES(rights),
                        raw=VALUES(raw),
                        updated_at=VALUES(updated_at);");

            foreach ($users as $user) {
                // Приведение данных к нужному формату
                $arr = [
                    ':user_id' => $user['id'],
                    ':name' => $user['name'] ?? null,
                    ':email' => $user['email'],
                    ':rights' => \json_encode($user['rights']),
                    ':raw' => \json_encode($user),
                    ':created_at' => now(),  // Устанавливаем вручную время создания
                    ':updated_at' => now()   // Устанавливаем вручную время обновления
                ];

                // Выполнение подготовленного запроса
                $stmt->execute($arr);
            }
            if ($pdo->inTransaction()) {
                $pdo->commit();  // Фиксация транзакции
                // Log::info('Транзакция успешно завершена user');
            }
        } catch (\Exception $e) {
            $pdo->rollBack();
            Log::error('Ошибка в транзакции: ' . $e->getMessage());
            throw $e;
        }
    }
}
