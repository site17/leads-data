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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id')->unique(); // ID контакта в AmoCRM
            $table->unsignedBigInteger('responsible_user_id')->nullable(); // Ответственный пользователь
            $table->unsignedBigInteger('group_id')->nullable(); // Группа, к которой относится контакт
            $table->unsignedBigInteger('account_id'); // ID аккаунта в AmoCRM
            $table->string('name'); // Имя контакта
            $table->string('first_name')->nullable(); // Имя контакта
            $table->string('last_name')->nullable(); // Фамилия контакта
            $table->string('phone')->nullable(); // Номер телефона
            $table->string('email')->nullable(); // Email контакта
            $table->json('custom_fields')->nullable(); // Дополнительные поля
            $table->timestamps();
            $table->softDeletes(); // Для мягкого удаления
            $table->text('raw_content')->nullable(); // Для хранения полного ответа от AmoCRM
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};