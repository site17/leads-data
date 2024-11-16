<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lead_id');
            $table->bigInteger('integration_id')->nullable();
            $table->bigInteger('responsible_user_id')->nullable();
            $table->bigInteger('group_id')->nullable();
            $table->bigInteger('status_id')->nullable();
            $table->bigInteger('pipeline_id')->nullable();
            $table->bigInteger('account_id')->nullable();
            $table->string('name')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->text('raw_content')->nullable();
            $table->timestamps(); // Эта строка добавляет поля created_at и updated_at автоматически
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}