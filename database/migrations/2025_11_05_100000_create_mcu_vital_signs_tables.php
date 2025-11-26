<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mcu_smp_vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            $table->integer('systolic_blood_pressure')->nullable();
            $table->integer('diastolic_blood_pressure')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->enum('heart_murmur', ['no', 'yes'])->default('no');
            $table->enum('lung_murmur', ['no', 'yes'])->default('no');
            $table->timestamps();
            
            $table->index('mcu_id');
        });

        Schema::create('mcu_sma_vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            $table->integer('systolic_blood_pressure')->nullable();
            $table->integer('diastolic_blood_pressure')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->enum('heart_murmur', ['no', 'yes'])->default('no');
            $table->enum('lung_murmur', ['no', 'yes'])->default('no');
            $table->timestamps();
            
            $table->index('mcu_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcu_sma_vitals');
        Schema::dropIfExists('mcu_smp_vitals');
    }
};