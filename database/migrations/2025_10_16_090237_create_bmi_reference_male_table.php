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
        Schema::create('bmi_reference_male', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('age_years')->unsigned();
            $table->tinyInteger('age_months')->unsigned();
            $table->decimal('very_thin_max', 4, 1);
            $table->decimal('thin_max', 4, 1);
            $table->decimal('lower_normal', 4, 1);
            $table->decimal('ideal', 4, 1);
            $table->decimal('upper_normal', 4, 1);
            $table->decimal('overweight_max', 4, 1);
            $table->decimal('very_overweight', 4, 1);
            $table->timestamps();
            
            $table->unique(['age_years', 'age_months'], 'unique_age_male');
            $table->index(['age_years', 'age_months'], 'idx_age_male');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bmi_reference_male');
    }
};