<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // DCTK Conclusion
        Schema::create('mcu_dctk_conclusion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });

        // SD Conclusion
        Schema::create('mcu_sd_conclusion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });

        // SMP Conclusion
        Schema::create('mcu_smp_conclusion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });

        // SMA Conclusion
        Schema::create('mcu_sma_conclusion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcu_sma_conclusion');
        Schema::dropIfExists('mcu_smp_conclusion');
        Schema::dropIfExists('mcu_sd_conclusion');
        Schema::dropIfExists('mcu_dctk_conclusion');
    }
};