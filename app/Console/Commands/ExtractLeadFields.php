<?php

namespace App\Console\Commands;

use PDO;
use PDOException;
use App\Models\Lead;
use App\Models\LeadFieldValue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExtractLeadFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:extract-lead-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Копирование из lead_fields';

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
        set_time_limit(0);
        // Используем chunk() для обработки данных батчами по 1000 записей
        Lead::whereNotNull('raw_content')->chunk(1000, function ($leads) use ($pdo) {
            foreach ($leads as $lead) {
                $this->info('Processing lead ID: ' . $lead->id);
                $rawContent = $lead->raw_content;

                if (is_array($rawContent) && isset($rawContent['custom_fields_values'])) {
                    $pdo->beginTransaction();
                    Log::info('Транзакция началась lead|filed');

                    $stmt = $pdo->prepare(
                        "INSERT INTO lead_fields_value (lead_id, field_id, value, created_at, updated_at)
                            VALUES (:lead_id, :field_id, :value, :created_at, :updated_at)
                            ON DUPLICATE KEY UPDATE value = VALUES(value), updated_at = VALUES(updated_at);"
                    );

                    foreach ($rawContent['custom_fields_values'] as $field) {
                        $fieldId = $field['field_id'];
                        // Записываем только field_id равное 89898989 или 3434343
                        if (in_array($fieldId, [89898989, 3434343])) {
                            if (!empty($field['values'])) {
                                foreach ($field['values'] as $valueObj) {
                                    // Проверяем наличие ключа 'value'
                                    if (isset($valueObj['value'])) {
                                        $value = $valueObj['value'];
                                        $stmt->execute([
                                            ':lead_id' => $lead->id,
                                            ':field_id' => $fieldId,
                                            ':value' => $value,
                                            ':created_at' => now(),
                                            ':updated_at' => now(),
                                        ]);
                                    } else {
                                        $this->info('No "value" found in field values for field ID: ' . $fieldId);
                                    }
                                }
                            } else {
                                $this->info('No values found for field ID: ' . $fieldId);
                            }
                        }
                    }

                    if ($pdo->inTransaction()) {
                        $pdo->commit();
                        Log::info('Транзакция успешно завершена lead|filed');
                    }
                } else {
                    $this->info('No valid raw_content for lead ID: ' . $lead->id);
                }
            }

            // Освобождение памяти и сбор мусора после каждой обработки батча
            gc_collect_cycles();
        });

        $this->info('Lead fields extracted successfully!');
        return 0;
    }
}
