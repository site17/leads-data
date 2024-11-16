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
        Schema::create('lead_fields_value', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id'); // Foreign key to leads table
            $table->unsignedBigInteger('field_id'); // Identifier for the field
            $table->text('value'); // Value for the field           
            // Add index for optimization
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_fields_value');
    }
};
