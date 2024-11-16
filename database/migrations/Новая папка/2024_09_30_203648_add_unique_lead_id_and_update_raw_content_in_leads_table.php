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
        Schema::table('leads', function (Blueprint $table) {
            // Добавление уникального индекса для lead_id
            // $table->unique('lead_id');

            // Изменение типа поля raw_content на longtext
            $table->longText('raw_content')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Удаление уникального индекса
            // $table->dropUnique(['lead_id']);

            // Возврат типа поля raw_content к предыдущему типу (например, text)
            $table->text('raw_content')->nullable()->change();
        });
    }
};
