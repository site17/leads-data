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
        Schema::create('user_rights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_pid');
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->unsignedBigInteger('role_pid');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Внешний ключ на таблицу users
            $table->foreign('user_pid')->references('id')->on('users')->onDelete('cascade');

            // Внешний ключ на таблицу roles
            $table->foreign('role_pid')->references('id')->on('user_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rights');
    }
};