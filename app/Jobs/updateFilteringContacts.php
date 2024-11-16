<?php

namespace App\Jobs;

use PDO;
use PDOException;
use Carbon\Carbon;
use App\Models\Contact;
use App\Models\OAuthToken;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class updateFilteringContacts implements ShouldQueue
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
    public function handle()
    {
        try {
            // В updateFilteringContacts

            $this->process();
        } catch (\Throwable $e) {
            Log::error('Ошибка при обновлении контактов: ' . $e->getMessage());
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
            Log::error('Ошибка подключения к базе данных: ' . $e->getMessage());
            die('Ошибка подключения к базе данных: ' . $e->getMessage());
        }

        // Проверка актуальности токена
        $tokenData = OAuthToken::where('service', 'amocrm')->first();

        if (!$tokenData) {
            Log::error('Токен AmoCRM не найден');
            return 1;  // Возврат ошибки
        }

        $lastUpdateTimestamp = Contact::getContactUpdate();
        $lastUpdateTimestamp = $lastUpdateTimestamp->subHours(6);
        $lastUpdateTimestamp = $lastUpdateTimestamp->timestamp;
        $currentTimestamp    = now()->addDay()->timestamp;
        // &page=3505
        $url = "https://{$tokenData->subdomain}.amocrm.ru/api/v4/contacts?with=leads&filter[updated_at][from]=$lastUpdateTimestamp&filter[updated_at][to]=$currentTimestamp&limit=50";

        set_time_limit(0);
        gc_collect_cycles();
        $i = 0;
        // Подготовленный запрос для вставки данных

        try {
            while (true) {
                $response = Http::withToken($tokenData->access_token)
                    ->get($url);
                if ($response->failed()) {
                    Log::error('Ошибка в запросе к AmoCRM');
                    throw new \Exception('Ошибка в запросе к AmoCRM');
                }
                $json = $response->json();
                $contacts = $json['_embedded']['contacts'];

                $pdo->beginTransaction();
                // Log::info('Транзакция началась contacts');
                $stmt = $pdo->prepare("
                INSERT INTO contacts (contact_id,responsible_user_id,group_id,account_id,name,first_name,last_name,phone,email,custom_fields,raw_content,created_at_amo, updated_at_amo,created_at,updated_at)
                VALUES (:contact_id,:responsible_user_id,:group_id,:account_id,:name,:first_name,:last_name,:phone,:email,:custom_fields,:raw_content,:created_at_amo, :updated_at_amo,:created_at,:updated_at)
                ON DUPLICATE KEY UPDATE
                    responsible_user_id=VALUES(responsible_user_id),
                    group_id=VALUES(group_id),
                    account_id=VALUES(account_id),
                    name=VALUES(name),
                    first_name=VALUES(first_name),
                    last_name=VALUES(last_name),
                    phone=VALUES(phone),
                    email=VALUES(email),
                    custom_fields=VALUES(custom_fields),
                    raw_content=VALUES(raw_content),
                    updated_at_amo = VALUES(updated_at_amo),
                    updated_at=VALUES(updated_at);");

                foreach ($contacts as $contact) {

                    $stmt->execute([
                        ':contact_id' => $contact['id'],
                        ':responsible_user_id' => $contact['responsible_user_id'] ?? null,
                        ':group_id' => $contact['group_id'] ?? null,
                        ':account_id' => $contact['account_id'],
                        ':name' => $contact['name'],
                        ':first_name' => $contact['first_name'] ?? null,
                        ':last_name' => $contact['last_name'] ?? null,
                        ':phone' => $contact['phone'] ?? null,
                        ':email' => $contact['email'] ?? null,
                        ':custom_fields' => $contact['custom_fields_values'] ? \json_encode($contact['custom_fields_values']) : null,
                        ':raw_content' => \json_encode($contact),
                        ':created_at_amo' => isset($contact['created_at']) ? Carbon::parse($contact['created_at']) : null,
                        ':updated_at_amo' => isset($contact['updated_at']) ? Carbon::parse($contact['updated_at']) : null,
                        ':created_at' => now(),  // Устанавливаем вручную время создания
                        ':updated_at' => now()   // Устанавливаем вручную время обновления
                    ]);
                }
                if ($pdo->inTransaction()) {
                    $pdo->commit();  // Фиксация транзакции
                    // Log::info('Транзакция успешно завершена contacts');
                }




                foreach ($contacts as $contact) {
                    // Log::info('contact to lead', [
                    //     '$contact'      => $contact,

                    // ]);
                    // Если у контакта есть связанные сделки, сохраняем их
                    if (isset($contact['_embedded']['leads'])) {
                        $pdo->beginTransaction();
                        // Log::info('Транзакция началась lead in contacts');
                        $stmt_leads = $pdo->prepare("INSERT IGNORE INTO leads (lead_id)VALUES (:lead_id)ON DUPLICATE KEY UPDATE
                           updated_at=VALUES(updated_at);");
                        foreach ($contact['_embedded']['leads'] as $lead) {
                            $stmt_leads->execute([
                                ':lead_id' => $lead['id']
                            ]);
                            // $stmt_leads->closeCursor();
                        }

                        if ($pdo->inTransaction()) {
                            $pdo->commit();  // Фиксация транзакции
                            // Log::info('Транзакция успешно завершена lead in contacts');
                        }
                    }
                    $contact_pid = Contact::where('contact_id', $contact['id'])->value('id');

                    // Если у контакта есть связанные сделки, сохраняем их
                    if (isset($contact['_embedded']['leads'])) {
                        $pdo->beginTransaction();
                        // Log::info('Транзакция началась lead_contacts');
                        $stmt_lead_contact = $pdo->prepare("
                        INSERT INTO contacts_lead (lead_pid,contact_pid,created_at,updated_at)
                        VALUES (:lead_pid,:contact_pid,:created_at,:updated_at)
                        ON DUPLICATE KEY UPDATE
                           updated_at=VALUES(updated_at);");
                        foreach ($contact['_embedded']['leads'] as $lead) {

                            $lead_pid = Lead::where('lead_id', $lead['id'])->value('id');

                            if ($lead_pid && $contact_pid) {
                                $stmt_lead_contact->execute([
                                    ':lead_pid' => $lead_pid,
                                    ':contact_pid' => $contact_pid,
                                    ':created_at' => now(),  // Устанавливаем вручную время создания
                                    ':updated_at' => now()   // Устанавливаем вручную время обновления
                                ]);
                                
                            } else {
                                // Логируем или выводим ошибку для отладки
                                // Log::warning('Lead or Contact PID is NULL', [
                                //     'lead_id' => $lead['id'],
                                //     'contact_id' => $contact['id'],
                                //     'lead_pid' => $lead_pid,
                                //     'contact_pid' => $contact_pid,
                                // ]);
                            }
                        }

                        if ($pdo->inTransaction()) {
                            $pdo->commit();  // Фиксация транзакции
                            // Log::info('Транзакция успешно завершена lead_contacts');
                        }
                    }
                }


                $i++;

                $next = $json['_links']['next']['href'] ?? null;
                if (!isset($next) || $next === '') {
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
