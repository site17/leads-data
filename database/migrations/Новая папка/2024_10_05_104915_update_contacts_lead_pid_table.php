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
            $table->unsignedBigInteger('contact_pid');
            $table->unsignedBigInteger('lead_pid');
            $table->foreign('contact_pid')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('lead_pid')->references('id')->on('leads')->onDelete('cascade');
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