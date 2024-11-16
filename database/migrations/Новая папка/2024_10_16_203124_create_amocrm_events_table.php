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
        Schema::create('amocrm_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id')->unique(); // ID события из AmoCRM
            $table->string('type'); // Тип события (например, lead_added)
            $table->bigInteger('entity_id'); // ID объекта (например, лида)
            $table->bigInteger('lead_pid'); // ID объекта (например, лида) id таблицы lead
            $table->string('entity_type'); // Тип объекта (lead, deal, contact)
            $table->bigInteger('created_by'); // ID пользователя, который создал событие
            $table->timestamp('created_at_amocrm'); // Время создания события в AmoCRM
            $table->text('value_after')->nullable(); // Значение после изменения
            $table->text('value_before')->nullable(); // Значение до изменения
            $table->longText('raw')->nullable();
            $table->bigInteger('account_id'); // ID аккаунта AmoCRM
            $table->timestamps(); // Время создания и обновления записи в БД
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amocrm_events');
    }
};
