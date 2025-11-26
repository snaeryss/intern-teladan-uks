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
        Schema::create('dcu_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dcu_id')->unique()->constrained('dcu')->onDelete('cascade');

            // Oklusi & Mukosa
            $table->text('occlusion')->nullable();
            $table->text('mucosal_notes')->nullable();

            // DMF
            $table->decimal('decayed_teeth', 4, 1)->default(0);
            $table->decimal('missing_teeth', 4, 1)->default(0);
            $table->decimal('filled_teeth', 4, 1)->default(0);

            // Dental Habits
            $table->string('brushing_frequency', 50)->nullable();
            $table->string('brushing_time', 100)->nullable();
            $table->enum('uses_toothpaste', ['Ya', 'Tidak'])->nullable();
            $table->enum('consumes_sweets', ['Ya', 'Tidak'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dcu_examinations');
    }
};
