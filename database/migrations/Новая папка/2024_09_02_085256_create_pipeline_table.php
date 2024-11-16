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
        Schema::create('pipeline', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pipeline_id')->unique(); // ID воронки от AmoCRM
            $table->string('name');
            $table->integer('sort');
            $table->boolean('is_main');
            $table->boolean('is_unsorted_on');
            $table->boolean('is_archive');
            $table->integer('account_id');
            $table->text('raw')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pipeline');
    }
};
