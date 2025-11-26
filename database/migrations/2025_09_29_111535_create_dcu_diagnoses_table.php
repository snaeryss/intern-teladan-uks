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
        
            Schema::create('dcu_diagnoses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dcu_id')->constrained('dcu')->onDelete('cascade');
                $table->foreignId('dental_diagnosis_id')->constrained('dental_diagnoses');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dcu_diagnoses');
    }
};
