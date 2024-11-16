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
        Schema::create('lead_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_field_id')->unique();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->integer('sort')->nullable();
            $table->string('type')->nullable();
            $table->string('entity_type')->nullable();
            $table->boolean('is_computed')->nullable();
            $table->string('currency')->nullable();
            $table->text('enums')->nullable();
            $table->text('raw')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_fields');
    }
};
