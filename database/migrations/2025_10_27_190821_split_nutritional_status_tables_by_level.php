<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mcu_nutritional_status') && !Schema::hasTable('mcu_nutritional_status_backup')) {
            DB::statement('CREATE TABLE mcu_nutritional_status_backup AS SELECT * FROM mcu_nutritional_status');
        }

        Schema::create('dctk_mcu_nutritional_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('head_circumference', 5, 2)->nullable();
            $table->decimal('arm_circumference', 5, 2)->nullable();
            $table->decimal('abdominal_circumference', 5, 2)->nullable();
            $table->string('bmi')->nullable();
            $table->enum('nutritional_status', [
                'very_thin',
                'thin',
                'normal',
                'overweight',
                'very_overweight'
            ])->nullable();
            $table->enum('weight_for_age', [
                'normal',
                'gizi_kurang',
                'gizi_lebih'
            ])->default('normal');
            $table->enum('anemia', ['tidak', 'ya'])->default('tidak');
            $table->timestamps();
            
            $table->index('mcu_id');
        });

        Schema::create('sd_mcu_nutritional_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('head_circumference', 5, 2)->nullable();
            $table->decimal('arm_circumference', 5, 2)->nullable();
            $table->decimal('abdominal_circumference', 5, 2)->nullable();
            $table->string('bmi')->nullable();
            $table->enum('nutritional_status', [
                'very_thin',
                'thin',
                'normal',
                'overweight',
                'very_overweight'
            ])->nullable();
            $table->enum('weight_for_age', [
                'normal',
                'gizi_kurang',
                'gizi_lebih'
            ])->default('normal');
            $table->enum('anemia', ['tidak', 'ya'])->default('tidak');
            $table->timestamps();
            
            $table->index('mcu_id');
        });

        Schema::create('smp_mcu_nutritional_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->string('bmi')->nullable();
            $table->enum('nutritional_status', [
                'very_thin',
                'thin',
                'normal',
                'overweight',
                'very_overweight'
            ])->nullable();
            $table->enum('height_for_age', [
                'normal',
                'pendek'
            ])->default('normal');
            $table->enum('anemia', ['tidak', 'ya'])->default('tidak');
            $table->timestamps();
            
            $table->index('mcu_id');
        });

        Schema::create('sma_mcu_nutritional_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->string('bmi')->nullable();
            $table->enum('nutritional_status', [
                'very_thin',
                'thin',
                'normal',
                'overweight',
                'very_overweight'
            ])->nullable();
            $table->enum('height_for_age', [
                'normal',
                'pendek'
            ])->default('normal');
            $table->enum('anemia', ['tidak', 'ya'])->default('tidak');
            $table->timestamps();
            
            $table->index('mcu_id');
        });

        if (Schema::hasTable('mcu_nutritional_status')) {
            DB::statement("
                INSERT INTO dctk_mcu_nutritional_status 
                (mcu_id, weight, height, head_circumference, arm_circumference, abdominal_circumference, 
                 bmi, nutritional_status, weight_for_age, anemia, created_at, updated_at)
                SELECT 
                    ns.mcu_id, ns.weight, ns.height, ns.head_circumference, ns.arm_circumference, 
                    ns.abdominal_circumference, ns.bmi, ns.nutritional_status, 
                    COALESCE(ns.weight_height_age, 'normal'), COALESCE(ns.anemia, 'tidak'),
                    ns.created_at, ns.updated_at
                FROM mcu_nutritional_status ns
                INNER JOIN mcu m ON ns.mcu_id = m.mcu_id
                INNER JOIN students s ON m.student_id = s.id
                WHERE LOWER(s.school_level) IN ('dctk', 'tk', 'paud')
            ");

            DB::statement("
                INSERT INTO sd_mcu_nutritional_status 
                (mcu_id, weight, height, head_circumference, arm_circumference, abdominal_circumference, 
                 bmi, nutritional_status, weight_for_age, anemia, created_at, updated_at)
                SELECT 
                    ns.mcu_id, ns.weight, ns.height, ns.head_circumference, ns.arm_circumference, 
                    ns.abdominal_circumference, ns.bmi, ns.nutritional_status, 
                    COALESCE(ns.weight_height_age, 'normal'), COALESCE(ns.anemia, 'tidak'),
                    ns.created_at, ns.updated_at
                FROM mcu_nutritional_status ns
                INNER JOIN mcu m ON ns.mcu_id = m.mcu_id
                INNER JOIN students s ON m.student_id = s.id
                WHERE LOWER(s.school_level) = 'sd'
            ");

            DB::statement("
                INSERT INTO smp_mcu_nutritional_status 
                (mcu_id, weight, height, bmi, nutritional_status, height_for_age, anemia, created_at, updated_at)
                SELECT 
                    ns.mcu_id, ns.weight, ns.height, ns.bmi, ns.nutritional_status,
                    CASE 
                        WHEN ns.weight_height_age = 'pendek' THEN 'pendek'
                        ELSE 'normal'
                    END,
                    COALESCE(ns.anemia, 'tidak'),
                    ns.created_at, ns.updated_at
                FROM mcu_nutritional_status ns
                INNER JOIN mcu m ON ns.mcu_id = m.mcu_id
                INNER JOIN students s ON m.student_id = s.id
                WHERE LOWER(s.school_level) = 'smp'
            ");

            DB::statement("
                INSERT INTO sma_mcu_nutritional_status 
                (mcu_id, weight, height, bmi, nutritional_status, height_for_age, anemia, created_at, updated_at)
                SELECT 
                    ns.mcu_id, ns.weight, ns.height, ns.bmi, ns.nutritional_status,
                    CASE 
                        WHEN ns.weight_height_age = 'pendek' THEN 'pendek'
                        ELSE 'normal'
                    END,
                    COALESCE(ns.anemia, 'tidak'),
                    ns.created_at, ns.updated_at
                FROM mcu_nutritional_status ns
                INNER JOIN mcu m ON ns.mcu_id = m.mcu_id
                INNER JOIN students s ON m.student_id = s.id
                WHERE LOWER(s.school_level) = 'sma'
            ");
        }

        Schema::dropIfExists('mcu_nutritional_status');
    }

    public function down(): void
    {
        if (Schema::hasTable('mcu_nutritional_status_backup')) {
            DB::statement('CREATE TABLE mcu_nutritional_status AS SELECT * FROM mcu_nutritional_status_backup');
            Schema::dropIfExists('mcu_nutritional_status_backup');
        }

        Schema::dropIfExists('sma_mcu_nutritional_status');
        Schema::dropIfExists('smp_mcu_nutritional_status');
        Schema::dropIfExists('sd_mcu_nutritional_status');
        Schema::dropIfExists('dctk_mcu_nutritional_status');
    }
};