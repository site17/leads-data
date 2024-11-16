<?php

namespace App\Console\Commands;

use PDO;
use PDOException;
use Carbon\Carbon;
use App\Models\Lead;
use App\Models\Contact;
use App\Models\OAuthToken;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class getFilteringContactsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-filtering-contacts-command';

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

        // Проверка актуальности токена
        $tokenData = OAuthToken::where('service', 'amocrm')->first();
        if (!$tokenData) {
            $this->error('Token not found');
            return 1;  // Возврат ошибки
        }
        $lastUpdateTimestamp = Contact::getContactUpdate();
        $this->info(\sprintf('изначальная дата %s', strval($lastUpdateTimestamp)));

        $lastUpdateTimestamp = $lastUpdateTimestamp->subHours(6);
        $this->info(\sprintf('старт %s', strval($lastUpdateTimestamp)));

        $lastUpdateTimestamp = $lastUpdateTimestamp->timestamp;
        $currentTimestamp    = now()->addDay()->timestamp;
        // &page=3505
        $url = "https://{$tokenData->subdomain}.amocrm.ru/api/v4/contacts?filter[updated_at][from]=$lastUpdateTimestamp&filter[updated_at][to]=$currentTimestamp&limit=200";

        set_time_limit(0);
        $i = 0;
        // Подготовленный запрос для вставки данных
        $stmt = $pdo->prepare("START TRANSACTION;
        INSERT INTO contacts (contact_id,responsible_user_id,group_id,account_id,name,first_name,last_name,phone,email,custom_fields,raw_content,created_at_amo, updated_at_amo)
        VALUES (:contact_id,:responsible_user_id,:group_id,:account_id,:name,:first_name,:last_name,:phone,:email,:custom_fields,:raw_content,:created_at_amo, :updated_at_amo)
        ON DUPLICATE KEY UPDATE
            contact_id=VALUES( contact_id),
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
            created_at_amo = VALUES(created_at_amo),
            updated_at_amo = VALUES(updated_at_amo);COMMIT;");

        $stmt_leads = $pdo->prepare("START TRANSACTION;
            INSERT INTO leads (lead_id)
            VALUES (:lead_id)
            ON DUPLICATE KEY UPDATE
                lead_id=VALUES(id);COMMIT;");

        $stmt_lead_contact = $pdo->prepare("START TRANSACTION;
            INSERT INTO contacts_lead (lead_pid,contact_pid)
            VALUES (:lead_pid,:contact_pid)
            ON DUPLICATE KEY UPDATE
                lead_pid=VALUES(lead_pid),
                contact_pid=VALUES(contact_pid);COMMIT;");

        while (true) {
            gc_collect_cycles();
            $ms = microtime(true);
            $response = Http::withToken($tokenData->access_token)
                ->get($url);
            $ms2 = microtime(true) - $ms;
            $this->info('$ms2 ' . (string)$ms2);
            // $this->info('$response ' . (string)$response);

            if ($response->successful()) {
                $json = $response->json();
                $contacts = $json['_embedded']['contacts'];
                $ms = microtime(true);

                $this->info(\sprintf('Лиды %s', sizeof($contacts)));
                $this->info(\sprintf('Итерация %s', $i));
                $this->info(\sprintf('память %s', \memory_get_usage()));
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
                    ]);
                    $stmt->closeCursor();
                }
                $this->info(
                    \sprintf('память 2 %s', \memory_get_usage())
                );
                $i++;

                foreach ($contacts as $contact) {
                    // Если у контакта есть связанные сделки, сохраняем их
                    if (isset($contact['_embedded']['leads']) && is_array($contact['_embedded']['leads'])) {
                        foreach ($contact['_embedded']['leads'] as $lead) {
                            $stmt_leads->execute([
                                ':lead_id' => $lead['id']
                            ]);
                            $stmt_leads->closeCursor();
                        }
                    }
                    $contact_pid = Contact::where('contact_id', $contact['id'])->value('id');

                    // Если у контакта есть связанные сделки, сохраняем их
                    if (isset($contact['_embedded']['leads']) && is_array($contact['_embedded']['leads'])) {
                        foreach ($contact['_embedded']['leads'] as $lead) {

                            $lead_pid = Lead::where('lead_id', $lead['id'])->value('id');

                            if ($lead_pid && $contact_pid) {
                                $stmt_lead_contact->execute([
                                    ':lead_pid' => $lead_pid,
                                    ':contact_pid' => $contact_pid,
                                ]);
                                $stmt_lead_contact->closeCursor();
                            } else {
                                // Логируем или выводим ошибку для отладки
                                Log::warning('Lead or Contact PID is NULL', [
                                    'lead_id' => $lead['id'],
                                    'contact_id' => $contact['id'],
                                    'lead_pid' => $lead_pid,
                                    'contact_pid' => $contact_pid,
                                ]);
                            }
                        }
                    }
                }




                $ms3 = microtime(true) - $ms;
                $this->info('$ms3 ' . (string)$ms3);
                Log::info('contacts', [
                    'url'      => $url,
                    'lead' => \sizeof($contact),
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
                $this->info('Failed to fetch contacts');
                return 1;
            }
        }
        $this->info('Contacts successfully synced');
        return 0;  // Успешное выполнение
    }
}