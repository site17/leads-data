<?php

namespace App\Http\Controllers\Amo;

use Carbon\Carbon;
use App\Models\Lead;
// use GuzzleHttp\Client;
use App\Models\Contact;
use App\Models\crmUser;
use App\Models\Pipeline;
use App\Models\Statuses;
use App\Models\LeadFields;
use App\Models\OAuthToken;
use Illuminate\Support\Str;
use App\Jobs\ProcessPodcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;


class AmoCrmController extends Controller
{
    // protected $client_id = '';
    protected $client_secret = '';
    // protected $redirect_uri = '';
    // protected $subdomain = '';

    public function redirectToAmo()
    {
        $state = Str::random(40);
        session(['amocrm_state' => $state]);

        $client_id = config('services.amocrm.client_id');
        $redirect_uri = config('services.amocrm.redirect_uri');
        $subdomain = config('services.amocrm.subdomain');

        $query = http_build_query([
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'state' => $state,
        ]);

        return redirect("https://{$subdomain}.amocrm.ru/oauth?{$query}");
    }

    public function callback(Request $request)
    {


        $code = config('services.amocrm.code');


        if (!$code) {
            Log::error('Authorization code not found in the request.');
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        // Получаем данные клиента из конфигурации или базы данных
        $client_id = config('services.amocrm.client_id');
        $client_secret = config('services.amocrm.client_secret');
        $redirect_uri = config('services.amocrm.redirect_uri');
        $subdomain = config('services.amocrm.subdomain');

        // Отправляем запрос на получение токенов
        $response = Http::asForm()->post("https://{$subdomain}.amocrm.ru/oauth2/access_token", [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if (!isset($data['access_token'])) {
                Log::error('Access token not found in AMOCRM response', ['response' => $data]);
                return response()->json(['error' => 'Access token not found in response'], 400);
            }

            // Сохраняем токены в базе данных
            OAuthToken::updateOrCreate(
                ['service' => 'amocrm'],
                [
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                    'expires_in' => now()->addSeconds($data['expires_in']),
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'redirect_uri' => $redirect_uri,
                    'subdomain' => $subdomain,
                ]
            );

            return redirect()->route('/crm/amo-leads');
        } else {
            Log::error('Failed to obtain access token from AMOCRM', ['response' => $response->body()]);
            return response()->json(['error' => 'Failed to obtain access token'], 400);
        }
    }
    public function refresh(Request $request)
    {
        // Получаем данные клиента из конфигурации или базы данных
        $tokenData = OAuthToken::where('service', 'amocrm')->first();
        try {
            echo $tokenData;
            // Отправляем запрос на получение токенов
            $response = Http::asForm()->post("https://{$tokenData->subdomain}.amocrm.ru/oauth2/access_token", [
                'client_id' =>  $tokenData->client_id,
                'client_secret' => $tokenData->client_secret,
                'grant_type' => 'refresh_token',
                'refresh_token' => $tokenData->refresh_token,
                'redirect_uri' => $tokenData->redirect_uri,
            ]);

            Log::info('refresh', $response);
        } catch (\Throwable $e) {
            Log::info('catch', [
                'exception' => $e,
            ]);
        }


        if ($response->successful()) {
            $data = $response->json();

            if (!isset($data['access_token'])) {
                Log::error('Access token not found in AMOCRM response', ['response' => $data]);
                return response()->json(['error' => 'Access token not found in response'], 400);
            }

            // Сохраняем токены в базе данных
            OAuthToken::updateOrCreate(
                ['service' => 'amocrm'],
                [
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                    'expires_in' => now()->addSeconds($data['expires_in']),
                ]
            );
        } else {
            Log::error('Failed to obtain access token from AMOCRM', ['response' => $response->body()]);

            return response()->json(['error' => 'Failed to obtain access token'], 400);
        }
    }
    // Дополнительные методы для работы с токенами и AmoCRM
    // public function getLeads()
    // {
    //     $tokenData = OAuthToken::where('service', 'amocrm')->first();

    //     if (!$tokenData) {
    //         return response()->json(['error' => 'Token not found'], 400);
    //     }

    //     // 2142
    //     $url = "https://{$tokenData->subdomain}.amocrm.ru/api/v4/leads?limit=50&page=2145";
    //     set_time_limit(0);
    //     while (true) {

    //         $ms = microtime(true);
    //         $response = Http::withToken($tokenData->access_token)
    //             ->get($url);
    //         $ms2 = microtime(true) - $ms;
    //         if ($response->successful()) {
    //             $json = $response->json();
    //             // dd($json);
    //             $leads = $json['_embedded']['leads'];
    //             // dd($leads);
    //             $ms = microtime(true);
    //             DB::beginTransaction(); // Начало транзакции
    //             foreach ($leads as $lead) {
    //                 Lead::updateOrCreate(
    //                     ['lead_id' => $lead['id']],
    //                     [
    //                         'name' => $lead['name'] ?? null,
    //                         'status_id' => $lead['status_id'] ?? null,
    //                         'pipeline_id' => $lead['pipeline_id'] ?? null,
    //                         'responsible_user_id' => $lead['responsible_user_id'] ?? null,
    //                         'price' => $lead['price'] ?? 0,
    //                         'created_by' => $lead['created_by'] ?? null,
    //                         'updated_by' => $lead['updated_by'] ?? null,
    //                         'closed_at' => isset($lead['closed_at']) ? Carbon::createFromTimestamp($lead['closed_at']) : null,
    //                         'is_deleted' => $lead['is_deleted'] ?? false,
    //                         'raw_content' => $lead, // Сохраняем весь ответ для дальнейшего анализа
    //                         'created_at_amo' => isset($lead['created_at']) ? Carbon::parse($lead['created_at']) : null,
    //                         'updated_at_amo' => isset($lead['updated_at']) ? Carbon::parse($lead['updated_at']) : null,
    //                         'account_id' => $lead['account_id'] ?? null,
    //                         'group_id' => $lead['group_id'] ?? null,
    //                     ]
    //                 );
    //             }
    //             DB::commit(); // Подтверждение транзакции
    //             $ms3 = microtime(true) - $ms;

    //             Log::info('leads', [
    //                 'url'      => $url,
    //                 'lead' => \sizeof($leads),
    //                 '_links'   => $json['_links'],
    //                 '$ms2' => $ms2,
    //                 '$ms3' => $ms3,
    //             ]);
    //             $next = $json['_links']['next']['href'];
    //             if (!isset($next) || $next === '') {
    //                 break;
    //             }
    //             $url = $json['_links']['next']['href'];
    //         } else {
    //             return response()->json(['error' => 'Failed to fetch leads'], 400);
    //         }
    //     }
    //     return response()->json(['message' => 'Leads successfully synced'], 200);
    // }

    //получаем контакты со связями с лидами
    public function getContacts()
    {
        // Проверка актуальности токена
        $tokenData = OAuthToken::where('service', 'amocrm')->first();

        if (!$tokenData) {
            return response()->json(['error' => 'Token not found'], 400);
        }

        $url = "https://{$tokenData->subdomain}.amocrm.ru/api/v4/contacts?with=leads&limit=50&page=3505";
        // $params = [
        //     'with' => 'leads'
        // ];
        set_time_limit(0);
        while (true) {
            // Запрос к API AmoCRM для получения контактов с привязанными сделками
            $response = Http::withToken($tokenData->access_token)
                ->get($url);

            if ($response->successful()) {
                $json = $response->json();
                // dd($json);
                $contacts = $json['_embedded']['contacts'];
                // dd($contacts);
                // Сохранение контактов и их сделок в базу данных
                Log::info('contacts', [
                    'url'      => $url,
                    'contacts' => \sizeof($contacts),
                    '_links'   => $json['_links'],
                ]);
                DB::beginTransaction(); // Начало транзакции

                foreach ($contacts as $contact) {
                    // Сохранение контакта
                    $savedContact = Contact::updateOrCreate(
                        ['contact_id' => $contact['id']],
                        [
                            'responsible_user_id' => $contact['responsible_user_id'] ?? null,
                            'group_id' => $contact['group_id'] ?? null,
                            'account_id' => $contact['account_id'],
                            'name' => $contact['name'],
                            'first_name' => $contact['first_name'] ?? null,
                            'last_name' => $contact['last_name'] ?? null,
                            'phone' => $contact['phone'] ?? null,
                            'email' => $contact['email'] ?? null,
                            'custom_fields' => $contact['custom_fields'] ?? null,
                            'raw_content' => $contact,
                        ]
                    );

                    // Если у контакта есть связанные сделки, сохраняем их
                    if (isset($contact['_embedded']['leads']) && is_array($contact['_embedded']['leads'])) {
                        // dd($contact);
                        // dd($contact['_embedded']['leads']);
                        foreach ($contact['_embedded']['leads'] as $lead) {

                            // Сохранение сделки
                            $savedLead = Lead::updateOrCreate(
                                ['lead_id' => $lead['id']],
                                // [
                                //     'name' => $lead['name'],
                                //     'price' => $lead['price'] ?? null,
                                //     'status_id' => $lead['status_id'] ?? null,
                                //     'pipeline_id' => $lead['pipeline_id'] ?? null,
                                // ]
                            );

                            // Сохранение связи между контактом и сделкой
                            DB::table('contacts_lead')->updateOrInsert(
                                ['contact_id' => $savedContact->id, 'lead_id' => $savedLead->id]
                            );
                        }
                    }
                }

                DB::commit(); // Подтверждение транзакции

                $next = $json['_links']['next']['href'];
                if (!isset($next) || $next === '') {
                    break;
                }

                $url = $json['_links']['next']['href'];
            } else {
                return response()->json(['error' => 'Ошибка при загрузке контактов.'], 400);
            }
        }
        return response()->json(['success' => 'Контакты и сделки успешно загружены.'], 200);
    }
    public function getPipelines()
    {
        // Проверка актуальности токена
        $tokenData = OAuthToken::where('service', 'amocrm')->first();

        if (!$tokenData) {
            return response()->json(['error' => 'Token not found'], 400);
        }

        // Запрос к API AmoCRM для получения воронок
        $response = Http::withToken($tokenData->access_token)
            ->get("https://{$tokenData->subdomain}.amocrm.ru/api/v4/leads/pipelines");


        if ($response->successful()) {
            $pipelines = $response->json()['_embedded']['pipelines'];

            foreach ($pipelines as $pipeline) {

                $savedPipeline = Pipeline::updateOrCreate(
                    [
                        'pipeline_id' => $pipeline['id'],  // Используйте правильное поле 'id' от AmoCRM
                    ],
                    [
                        'name' => $pipeline['name'],
                        'sort' => $pipeline['sort'],
                        'account_id' => $pipeline['account_id'],
                        'is_unsorted_on' => $pipeline['is_unsorted_on'],
                        'is_archive' => $pipeline['is_archive'],
                        'is_main' => $pipeline['is_main'],
                        'raw' => $pipeline,
                    ]
                );
                // Сохранение статусов
                $this->syncStatuses($pipeline['_embedded']['statuses'] ?? [], $savedPipeline->id);
            }

            return response()->json(['message' => 'Pipelines successfully synced'], 200);
        } else {
            dd($response);
            return response()->json(['error' => 'Failed to fetch pipelines'], 400);
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
    public function getUser()
    {
        // Проверка актуальности токена
        $tokenData = OAuthToken::where('service', 'amocrm')->first();

        if (!$tokenData) {
            return response()->json(['error' => 'Token not found'], 400);
        }

        // Запрос к API AmoCRM для получения воронок
        $response = Http::withToken($tokenData->access_token)
            ->get("https://{$tokenData->subdomain}.amocrm.ru/api/v4/users");


        if ($response->successful()) {
            $users = $response->json()['_embedded']['users'];
            // dd($users);
            Log::info('users', [

                'users' => \sizeof($users),

            ]);
            foreach ($users as $user) {

                $savedUser = crmUser::updateOrCreate(
                    [
                        'user_id' => $user['id'],  // Используйте правильное поле 'id' от AmoCRM
                    ],
                    [
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'rights' => $user['rights'],
                        'raw' => $user,
                    ]
                );
            }

            return response()->json(['message' => 'Users successfully synced'], 200);
        } else {

            return response()->json(['error' => 'Failed to fetch users'], 400);
        }
    }

    public function handleAmoCrmWebhook(Request $request)
    {
        // $tokenData = OAuthToken::where('service', 'amocrm')->first();

        // if (!$tokenData) {
        //     return response()->json(['error' => 'Token not found'], 400);
        // }

        // Валидация входящих данных
        $data = $request->getContent();
        // dd($data);

        // Логирование полученных данных (для отладки)
        Log::info('Webhook from amoCRM', [$data]);

        // if (isset($data['leads']['add']) || isset($data['leads']['update'])) {
        //     $leads = $data['leads']['add'] ?? $data['leads']['update'];

        //     foreach ($leads as $lead) {
        //         Lead::updateOrCreate(
        //             ['lead_id' => $lead['id']],
        //             [
        //                 'name' => $lead['name'] ?? null,
        //                 'status_id' => $lead['status_id'] ?? null,
        //                 'pipeline_id' => $lead['pipeline_id'] ?? null,
        //                 'responsible_user_id' => $lead['responsible_user_id'] ?? null,
        //                 'price' => $lead['price'] ?? 0,
        //                 'created_by' => $lead['created_by'] ?? null,
        //                 'updated_by' => $lead['updated_by'] ?? null,
        //                 'closed_at' => isset($lead['closed_at']) ? Carbon::createFromTimestamp($lead['closed_at']) : null,
        //                 'is_deleted' => $lead['is_deleted'] ?? false,
        //                 'raw_content' => json_encode($lead), // Сохраняем весь ответ для дальнейшего анализа
        //             ]
        //         );
        //     }
        // }

        return response()->json(['status' => 'success']);
    }
    public function getLeadFields()
    {
        // Проверка актуальности токена
        $tokenData = OAuthToken::where('service', 'amocrm')->first();

        if (!$tokenData) {
            return response()->json(['error' => 'Token not found'], 400);
        }

        // Запрос к API AmoCRM для получения воронок
        $response = Http::withToken($tokenData->access_token)
            ->get("https://{$tokenData->subdomain}.amocrm.ru/api/v4/leads/custom_fields");


        if ($response->successful()) {
            $fields = $response->json()['_embedded']['custom_fields'];

            foreach ($fields as $field) {

                $savedUser = LeadFields::updateOrCreate(
                    [
                        'custom_field_id' => $field['id'],  // Используйте правильное поле 'id' от AmoCRM
                    ],
                    [
                        'name' => $field['name'],
                        'code' => $field['code'],
                        'sort' => $field['sort'],
                        'type' => $field['type'],
                        'entity_type' => $field['entity_type'],
                        'is_predefined' => $field['is_predefined'],
                        // 'settings' => json_encode($field['settings']),
                        // 'remind' => $field['remind'],
                        'is_api_only' => $field['is_api_only'],
                        'group_id' => $field['group_id'],
                        // 'is_api_only' => $field['is_api_only'],
                        'required_statuses' => $field['required_statuses'],
                        'enums' => $field['enums'],
                        'raw' => $field,
                    ]
                );
            }

            return response()->json(['message' => 'Fields successfully synced'], 200);
        } else {

            return response()->json(['error' => 'Failed to fetch fields'], 400);
        }
    }
    public function getLeadFilter()
    {
        $tokenData = OAuthToken::where('service', 'amocrm')->first();

        if (!$tokenData) {
            return response()->json(['error' => 'Token not found'], 400);
        }
        $lastUpdateTimestamp = strtotime(Lead::getLastUpdate());
        $currentTimestamp = now()->timestamp;

        $response = Http::withToken($tokenData->access_token)
            ->get("https://{$tokenData->subdomain}.amocrm.ru/api/v4/leads?filter[updated_at][from]=$lastUpdateTimestamp&filter[updated_at][to]=$currentTimestamp");
       
        if ($response->successful()) {
            $leads = $response->json()['_embedded']['leads'];

            foreach ($leads as $lead) {
                Lead::updateOrCreate(
                    ['lead_id' => $lead['id']],
                    [
                        'name' => $lead['name'] ?? null,
                        'status_id' => $lead['status_id'] ?? null,
                        'pipeline_id' => $lead['pipeline_id'] ?? null,
                        'responsible_user_id' => $lead['responsible_user_id'] ?? null,
                        'price' => $lead['price'] ?? 0,
                        'group_id'
                        => $lead['group_id'] ?? null,
                        'created_by' => $lead['created_by'] ?? null,
                        'updated_by' => $lead['updated_by'] ?? null,
                        'closed_at' => isset($lead['closed_at']) ? Carbon::createFromTimestamp($lead['closed_at']) : null,
                        'is_deleted' => $lead['is_deleted'] ?? false,
                        'raw_content' => $lead, // Сохраняем весь ответ для дальнейшего анализа
                    ]
                );
            }

            return response()->json(['message' => 'Leads successfully synced'], 200);
        } else {
            return response()->json(['error' => 'Failed to fetch leads'], 400);
        }
    }
    public function someMethod()
    {
        // Отправка задачи в очередь на выполнение
        ProcessPodcast::dispatch();
    }
}
