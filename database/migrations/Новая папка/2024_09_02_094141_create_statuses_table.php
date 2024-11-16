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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status_id')->unique(); // ID статуса от AmoCRM
            $table->unsignedBigInteger('pipeline_pid'); // ID воронки, к которой относится статус
            $table->string('name'); // Название статуса
            $table->integer('sort'); // Порядок сортировки
            $table->boolean('is_editable'); // Можно ли редактировать статус
            $table->string('color')->nullable(); // Цвет статуса (если есть)
            $table->integer('type');
            $table->integer('account_id');
            $table->text('raw')->nullable();
            $table->timestamps();
            // Внешний ключ для связи со статусами
            $table->foreign('pipeline_pid')->references('id')->on('pipeline')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
