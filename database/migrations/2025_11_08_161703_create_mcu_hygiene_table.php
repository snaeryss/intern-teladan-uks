<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SMP Hygiene
        Schema::create('mcu_smp_hygiene', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            $table->enum('hair', ['healthy', 'unhealthy'])->default('healthy');
            $table->enum('skin_patches', ['healthy', 'unhealthy'])->default('healthy');
            $table->text('skin_patches_notes')->nullable();
            $table->enum('scaly_skin', ['no', 'yes'])->default('no');
            $table->enum('bruised_skin', ['no', 'yes'])->default('no');
            $table->enum('cut_skin', ['no', 'yes'])->default('no');
            $table->enum('sores', ['no', 'yes'])->default('no');
            $table->enum('hard_to_heal_sores', ['no', 'yes'])->default('no');
            $table->enum('injection_marks', ['no', 'yes'])->default('no');
            $table->enum('nails', ['healthy', 'dirty'])->default('healthy');
            
            $table->timestamps();
            $table->index('mcu_id');
        });

        // SMA Hygiene
        Schema::create('mcu_sma_hygiene', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            $table->enum('hair', ['healthy', 'unhealthy'])->default('healthy');
            $table->enum('skin_patches', ['healthy', 'unhealthy'])->default('healthy');
            $table->text('skin_patches_notes')->nullable();
            $table->enum('scaly_skin', ['no', 'yes'])->default('no');
            $table->enum('bruised_skin', ['no', 'yes'])->default('no');
            $table->enum('cut_skin', ['no', 'yes'])->default('no');
            $table->enum('sores', ['no', 'yes'])->default('no');
            $table->enum('hard_to_heal_sores', ['no', 'yes'])->default('no');
            $table->enum('injection_marks', ['no', 'yes'])->default('no');
            $table->enum('nails', ['healthy', 'dirty'])->default('healthy');
            
            $table->timestamps();
            $table->index('mcu_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcu_sma_hygiene');
        Schema::dropIfExists('mcu_smp_hygiene');
    }
};