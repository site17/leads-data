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
            // Убедитесь, что столбец pipeline_id существует и имеет правильный тип данных
            if (!Schema::hasColumn('leads', 'pipeline_id')) {
                $table->unsignedBigInteger('pipeline_id')->nullable();
            }

            // Добавляем внешний ключ
            $table->foreign('pipeline_id')
                ->references('pipeline_id')
                ->on('pipeline')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Удаление внешнего ключа и столбца
            $table->dropForeign(['pipeline_id']);
            $table->dropColumn('pipeline_id');
        });
    }
};
