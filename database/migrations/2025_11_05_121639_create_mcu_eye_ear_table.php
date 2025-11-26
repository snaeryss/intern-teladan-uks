<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mcu_dctk_eye_ear', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');

            $table->enum('outer_eye', ['normal', 'unhealthy'])->default('normal');
            $table->text('outer_eye_notes')->nullable();
            $table->enum('visual_acuity', ['normal', 'low_vision', 'blindness'])->default('normal');
            $table->text('visual_acuity_notes')->nullable();
            $table->enum('glasses', ['no', 'yes'])->default('no');
            $table->text('glasses_notes')->nullable();
            $table->enum('eye_infection', ['no', 'yes'])->default('no');
            $table->text('eye_infection_notes')->nullable();
            $table->text('other_eye_problems')->nullable();

            $table->enum('outer_ear', ['healthy', 'unhealthy'])->default('healthy');
            $table->text('outer_ear_notes')->nullable();
            $table->enum('earwax', ['no', 'yes'])->default('no');
            $table->text('earwax_notes')->nullable();
            $table->enum('ear_infection', ['no', 'yes'])->default('no');
            $table->text('ear_infection_notes')->nullable();
            $table->enum('hearing_acuity', ['normal', 'impaired'])->default('normal');
            $table->text('hearing_acuity_notes')->nullable();
            $table->text('other_ear_problems')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });

        Schema::create('mcu_sd_eye_ear', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');

            $table->enum('outer_eye', ['normal', 'unhealthy'])->default('normal');
            $table->text('outer_eye_notes')->nullable();
            $table->enum('visual_acuity', ['normal', 'low_vision', 'blindness'])->default('normal');
            $table->text('visual_acuity_notes')->nullable();
            $table->enum('glasses', ['no', 'yes'])->default('no');
            $table->text('glasses_notes')->nullable();
            $table->enum('eye_infection', ['no', 'yes'])->default('no');
            $table->text('eye_infection_notes')->nullable();
            $table->text('other_eye_problems')->nullable();

            $table->enum('outer_ear', ['healthy', 'unhealthy'])->default('healthy');
            $table->text('outer_ear_notes')->nullable();
            $table->enum('earwax', ['no', 'yes'])->default('no');
            $table->text('earwax_notes')->nullable();
            $table->enum('ear_infection', ['no', 'yes'])->default('no');
            $table->text('ear_infection_notes')->nullable();
            $table->enum('hearing_acuity', ['normal', 'impaired'])->default('normal');
            $table->text('hearing_acuity_notes')->nullable();
            $table->text('other_ear_problems')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });

        Schema::create('mcu_smp_eye_ear', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            $table->enum('outer_eye', ['normal', 'unhealthy'])->default('normal');
            $table->enum('visual_acuity', ['normal', 'low_vision', 'blindness', 'refractive_disorder'])->default('normal');
            $table->text('visual_acuity_notes')->nullable();
            $table->enum('color_blindness', ['no', 'yes'])->default('no');
            $table->enum('eye_infection', ['no', 'yes'])->default('no');

            $table->enum('outer_ear', ['healthy', 'unhealthy'])->default('healthy');
            $table->enum('earwax', ['no', 'yes'])->default('no');
            $table->enum('ear_infection', ['no', 'yes'])->default('no');
            $table->text('other_ear_problems')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });

        Schema::create('mcu_sma_eye_ear', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
 
            $table->enum('outer_eye', ['normal', 'unhealthy'])->default('normal');
            $table->enum('visual_acuity', ['normal', 'low_vision', 'blindness', 'refractive_disorder'])->default('normal');
            $table->text('visual_acuity_notes')->nullable();
            $table->enum('color_blindness', ['no', 'yes'])->default('no');
            $table->enum('eye_infection', ['no', 'yes'])->default('no');

            $table->enum('outer_ear', ['healthy', 'unhealthy'])->default('healthy');
            $table->enum('earwax', ['no', 'yes'])->default('no');
            $table->enum('ear_infection', ['no', 'yes'])->default('no');
            $table->text('other_ear_problems')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcu_sma_eye_ear');
        Schema::dropIfExists('mcu_smp_eye_ear');
        Schema::dropIfExists('mcu_sd_eye_ear');
        Schema::dropIfExists('mcu_dctk_eye_ear');
    }
};