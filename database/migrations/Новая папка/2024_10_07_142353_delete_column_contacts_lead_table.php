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


            $table->dropForeign(['contacts_lead_contacts_lead_field_pid_foreign_foreign']);
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
