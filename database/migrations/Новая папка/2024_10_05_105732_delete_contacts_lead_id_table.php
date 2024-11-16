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
        Schema::table('contacts_lead', function (Blueprint $table) {

            $table->unsignedBigInteger('contact_pid'); // Identifier for the field
            $table->dropForeign(['field_pid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts_lead', function (Blueprint $table) {
            //
        });
    }
};
