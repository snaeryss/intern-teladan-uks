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
        Schema::create('mcu_nutritional_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcu_id')->constrained('mcu', 'mcu_id')->onDelete('cascade');
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('head_circumference', 5, 2)->nullable();
            $table->decimal('arm_circumference', 5, 2)->nullable();
            $table->decimal('abdominal_circumference', 5, 2)->nullable();
            $table->decimal('bmi', 5, 2)->nullable();
            $table->enum('nutritional_status', [
                'very_thin',
                'thin',
                'normal',
                'overweight',
                'very_overweight'
            ])->nullable();
            $table->enum('weight_height_age', [
                'normal',
                'gizi_kurang',
                'gizi_lebih',
                'pendek'
            ])->nullable();
            $table->enum('anemia', ['tidak', 'ya'])->default('tidak');
            $table->timestamps();
            
            $table->index('mcu_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_nutritional_status');
    }
};