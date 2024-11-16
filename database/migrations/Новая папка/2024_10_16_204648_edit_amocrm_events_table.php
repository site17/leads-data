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
        Schema::table('amocrm_events', function (Blueprint $table) {
            $table->text('value_after')->nullable()->change(); // Значение после изменения
            $table->text('value_before')->nullable()->change(); // Значение до изменения
            $table->longText('raw')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amocrm_events', function (Blueprint $table) {
            //
        });
    }
};