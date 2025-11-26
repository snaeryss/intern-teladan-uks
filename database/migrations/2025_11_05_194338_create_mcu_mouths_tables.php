<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // DCTK Mouth
        Schema::create('mcu_dctk_mouth', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            // Rongga Mulut
            $table->enum('oral_cleft', ['no', 'yes'])->default('no');
            $table->enum('angular_cheilitis', ['no', 'yes'])->default('no');
            $table->enum('stomatitis', ['no', 'yes'])->default('no');
            $table->enum('coated_tongue', ['no', 'yes'])->default('no');
            $table->enum('other_lesions', ['no', 'yes'])->default('no');
            $table->text('other_mouth_problems')->nullable();
            
            // Gigi & Gusi
            $table->enum('caries', ['no', 'yes'])->default('no');
            $table->text('caries_notes')->nullable();
            $table->enum('misaligned_teeth', ['no', 'yes'])->default('no');
            $table->text('other_teeth_problems')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });

        // SD Mouth
        Schema::create('mcu_sd_mouth', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            // Rongga Mulut
            $table->enum('oral_cleft', ['no', 'yes'])->default('no');
            $table->enum('angular_cheilitis', ['no', 'yes'])->default('no');
            $table->enum('stomatitis', ['no', 'yes'])->default('no');
            $table->enum('coated_tongue', ['no', 'yes'])->default('no');
            $table->enum('other_lesions', ['no', 'yes'])->default('no');
            $table->text('other_mouth_problems')->nullable();
            
            // Gigi & Gusi
            $table->enum('caries', ['no', 'yes'])->default('no');
            $table->text('caries_notes')->nullable();
            $table->enum('misaligned_teeth', ['no', 'yes'])->default('no');
            $table->text('other_teeth_problems')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });

        // SMP Mouth
        Schema::create('mcu_smp_mouth', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            // Rongga Mulut
            $table->enum('oral_cleft', ['no', 'yes'])->default('no');
            $table->enum('angular_cheilitis', ['no', 'yes'])->default('no');
            $table->enum('stomatitis', ['no', 'yes'])->default('no');
            $table->enum('coated_tongue', ['no', 'yes'])->default('no');
            $table->enum('other_lesions', ['no', 'yes'])->default('no');
            $table->text('other_mouth_problems')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });

        // SMA Mouth
        Schema::create('mcu_sma_mouth', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            // Rongga Mulut
            $table->enum('oral_cleft', ['no', 'yes'])->default('no');
            $table->enum('angular_cheilitis', ['no', 'yes'])->default('no');
            $table->enum('stomatitis', ['no', 'yes'])->default('no');
            $table->enum('coated_tongue', ['no', 'yes'])->default('no');
            $table->enum('other_lesions', ['no', 'yes'])->default('no');
            $table->text('other_mouth_problems')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcu_sma_mouth');
        Schema::dropIfExists('mcu_smp_mouth');
        Schema::dropIfExists('mcu_sd_mouth');
        Schema::dropIfExists('mcu_dctk_mouth');
    }
};