<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('service');             // Название сервиса, например, 'amocrm'
            $table->text('access_token');         // Access token
            $table->text('refresh_token');        // Refresh token
            $table->dateTime('expires_in');       // Время истечения токена
            $table->string('client_id');          // ID клиента
            $table->text('client_secret');        // Секрет клиента
            $table->text('redirect_uri');         // URI перенаправления
            $table->string('subdomain')->nullable(); // Поддомен, если применимо
            $table->timestamps();                 // created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_tokens');
    }
}
