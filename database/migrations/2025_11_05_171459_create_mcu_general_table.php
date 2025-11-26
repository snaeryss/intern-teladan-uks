mcu_general

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // DCTK General Examination
        Schema::create('mcu_dctk_general', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            // Head section
            $table->enum('eyes_hygiene', ['healthy', 'unhealthy'])->default('healthy');
            $table->text('eyes_hygiene_notes')->nullable();
            $table->enum('nose_hygiene', ['healthy', 'unhealthy'])->default('healthy');
            $table->text('nose_hygiene_notes')->nullable();
            $table->enum('oral_cavity', ['no', 'yes'])->default('no');
            $table->text('oral_cavity_notes')->nullable();
            
            // Thorax section
            $table->enum('heart', ['no', 'yes'])->default('no');
            $table->text('heart_notes')->nullable();
            $table->enum('lungs', ['no', 'yes'])->default('no');
            $table->text('lungs_notes')->nullable();
            $table->enum('neurology', ['no', 'yes'])->default('no');
            $table->text('neurology_notes')->nullable();
            
            // Personal hygiene section
            $table->enum('hair', ['no', 'yes'])->default('no');
            $table->text('hair_notes')->nullable();
            $table->enum('skin', ['no', 'yes'])->default('no');
            $table->text('skin_notes')->nullable();
            $table->enum('nails', ['healthy', 'dirty'])->default('healthy');
            $table->text('nails_notes')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });

        // SD General Examination (struktur sama dengan DCTK)
        Schema::create('mcu_sd_general', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            
            // Head section
            $table->enum('eyes_hygiene', ['healthy', 'unhealthy'])->default('healthy');
            $table->text('eyes_hygiene_notes')->nullable();
            $table->enum('nose_hygiene', ['healthy', 'unhealthy'])->default('healthy');
            $table->text('nose_hygiene_notes')->nullable();
            $table->enum('oral_cavity', ['no', 'yes'])->default('no');
            $table->text('oral_cavity_notes')->nullable();
            
            // Thorax section
            $table->enum('heart', ['no', 'yes'])->default('no');
            $table->text('heart_notes')->nullable();
            $table->enum('lungs', ['no', 'yes'])->default('no');
            $table->text('lungs_notes')->nullable();
            $table->enum('neurology', ['no', 'yes'])->default('no');
            $table->text('neurology_notes')->nullable();
            
            // Personal hygiene section
            $table->enum('hair', ['no', 'yes'])->default('no');
            $table->text('hair_notes')->nullable();
            $table->enum('skin', ['no', 'yes'])->default('no');
            $table->text('skin_notes')->nullable();
            $table->enum('nails', ['healthy', 'dirty'])->default('healthy');
            $table->text('nails_notes')->nullable();
            
            $table->timestamps();
            $table->index('mcu_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcu_sd_general');
        Schema::dropIfExists('mcu_dctk_general');
    }
};