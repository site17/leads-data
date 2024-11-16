<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('amocrm_notes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('note_id')->unique();  // ID примечания
            $table->bigInteger('entity_id');  // ID сущности (лид)
            $table->bigInteger('created_by');  // Кто создал примечание
            $table->bigInteger('updated_by');  // Кто обновил примечание
            $table->dateTime('created_at_amocrm');  // Время создания в AmoCRM
            $table->dateTime('updated_at_amocrm');  // Время обновления в AmoCRM
            $table->bigInteger('responsible_user_id');  // Ответственный пользователь
            $table->bigInteger('group_id');  // ID группы, если используется
            $table->string('note_type');  // Тип примечания (например, 'common' или 'attachment')
            $table->text('params')->nullable();  // Поле для хранения параметров примечания (например, текст, файлы)
            $table->bigInteger('account_id');  // ID аккаунта
            $table->longText('raw')->nullable();  // Поле для хранения параметров примечания (например, текст, файлы)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amocrm_notes');
    }
};
