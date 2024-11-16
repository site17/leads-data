<?php

namespace App\Console\Commands;

use App\Models\OAuthToken;
use App\Jobs\ProcessPodcast;
use App\Jobs\refreshTockenJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class refreshTockenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-tocken-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh token every day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subdomain = config('services.amocrm.subdomain');
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

                $this->error('Access token not found in AMOCRM response');
                return 1;  // Возврат ошибки
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
            $this->info('Failed to obtain access token');
            return 1;  // Возврат ошибки

        }
    }
}