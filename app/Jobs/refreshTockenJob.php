<?php

namespace App\Jobs;

use App\Models\OAuthToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class refreshTockenJob implements ShouldQueue
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
        // Получаем данные клиента из конфигурации или базы данных
        $tokenData = OAuthToken::where('service', 'amocrm')->first();
        try {

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

                // $this->error('Access token not found in AMOCRM response');
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
            // $this->info('Failed to obtain access token');
            return 1;  // Возврат ошибки

        }
    }
}
