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
        Schema::create('crm_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable(); // Телефон пользователя
            $table->string('role')->nullable(); // Роль или должность пользователя
            $table->boolean('is_admin')->default(false); // Администратор ли этот пользователь
            $table->boolean('is_active')->default(true); // Активен ли пользователь в AmoCRM
            $table->unsignedBigInteger('group_id')->nullable(); // ID группы пользователя в AmoCRM
            $table->unsignedBigInteger('account_id'); // ID аккаунта AmoCRM
            $table->text('raw')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_user');
    }
};
