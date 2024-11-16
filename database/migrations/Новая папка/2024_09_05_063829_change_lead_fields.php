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
        Schema::table('lead_fields', function (Blueprint $table) {

            $table->boolean('is_predefined');
            $table->text('settings')->nullable();
            $table->string('remind')->nullable();
            $table->boolean('is_api_only');
            $table->string('group_id')->nullable();
            $table->text('required_statuses')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
