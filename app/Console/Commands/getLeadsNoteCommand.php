<?php

namespace App\Console\Commands;

use PDO;
use PDOException;
use Carbon\Carbon;
use App\Models\Note;
use App\Models\OAuthToken;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class getLeadsNoteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-leads-note-command';

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
            die('Ошибка подключения к базе данных: ' . $e->getMessage());
        }

        $tokenData = OAuthToken::where('service', 'amocrm')->first();

        if (!$tokenData) {
            $this->error('Token not found');
            return 1;  // Возврат ошибки
        }

        $currentTimestamp    = now()->addDay()->timestamp;

        // загружаю с 01.01.2024 = 1704067200 01.09.2024 = 1725148800
        $url = "https://{$tokenData->subdomain}.amocrm.ru/api/v4/leads/notes?filter[updated_at][from]=1725148800&filter[updated_at][to]=$currentTimestamp&limit=50";

        set_time_limit(0);
        $i = 0;
        try {
            while (true) {
                gc_collect_cycles();
                $response = Http::timeout(60)->withToken($tokenData->access_token)->get($url);
                if ($response->failed()) {
                    Log::error('Ошибка в запросе к AmoCRM');
                    throw new \Exception('Ошибка в запросе к AmoCRM');
                }
                $json = $response->json();
                $notes = $json['_embedded']['notes'];

                $this->info(\sprintf('Лиды %s', sizeof($notes)));
                $this->info(\sprintf('Итерация %s', $i));
                $this->info(\sprintf('память %s', \memory_get_usage()));

                $pdo->beginTransaction();
                // Log::info('Транзакция началась notes');
                // Подготовленный запрос для вставки данных
                $stmt = $pdo->prepare("INSERT INTO amocrm_notes (note_id,entity_id,created_by,updated_by, created_at_amocrm,updated_at_amocrm,responsible_user_id,group_id,note_type,params, account_id,raw,created_at,updated_at)
                      VALUES (:note_id,:entity_id, :created_by,:updated_by, :created_at_amocrm,:updated_at_amocrm,:responsible_user_id,:group_id,:note_type,:params, :account_id,:raw,:created_at,:updated_at)
                      ON DUPLICATE KEY UPDATE
                          entity_id=VALUES(entity_id),
                          created_by=VALUES(created_by),
                          updated_by=VALUES(updated_by), 
                          created_at_amocrm=VALUES(created_at_amocrm),
                          updated_at_amocrm=VALUES(updated_at_amocrm),
                          responsible_user_id=VALUES(responsible_user_id),
                          group_id=VALUES(group_id),
                          note_type=VALUES(note_type),
                          params=VALUES(params), 
                          account_id=VALUES(account_id),
                          raw=VALUES(raw),
                          updated_at=VALUES(updated_at);
                    ");
                foreach ($notes as $note) {
                    // Приводим данные к нужному формату и вставляем через PDO
                    $stmt->execute([
                        ':note_id' => $note['id'],
                        ':entity_id' => $note['entity_id'] ?? null,
                        ':created_by' => $note['created_by'] ?? null,
                        ':updated_by' => $note['updated_by'] ?? null,
                        ':created_at_amocrm' => isset($note['created_at']) ? Carbon::parse($note['created_at']) : null,
                        ':updated_at_amocrm' => isset($note['updated_at']) ? Carbon::parse($note['updated_at']) : null,
                        ':responsible_user_id' => $note['responsible_user_id'] ?? null,
                        ':group_id' => $note['group_id'] ?? null,
                        ':note_type' => $note['note_type'] ?? null,
                        ':params' => \json_encode($note['params']) ?? null,
                        ':account_id' => $note['account_id'] ?? null,
                        ':raw' => \json_encode($note),
                        ':created_at' => now(),  // Устанавливаем вручную время создания
                        ':updated_at' => now()   // Устанавливаем вручную время обновления

                    ]);
                }
                if ($pdo->inTransaction()) {
                    $pdo->commit();  // Фиксация транзакции
                    Log::info('Транзакция успешно завершена note');
                }
                // $this->info(
                //     \sprintf('память 2 %s', \memory_get_usage())
                // );
                $i++;

                // Переходим на следующую страницу
                $next = $json['_links']['next']['href'] ?? null;
                if (!$next) {
                    break;
                }

                Log::info('notes', [
                    'url'      => $url,
                    '_links'   => $next,
                ]);
                $url = $next;
                // } else {
                //     $this->info('Failed to fetch notes');
                //     return 1;  // Возврат ошибки
                // }
            }
        } catch (\Exception $e) {
            $pdo->rollBack();
            Log::error('Ошибка в транзакции: ' . $e->getMessage());
            throw $e;
        }

        // $this->info('Notes successfully synced(updated)');
        // return 0;
    }
}
