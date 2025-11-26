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
        Schema::create('dcu', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->date('date');
            $table->boolean('is_finish')->default(false);
            $table->foreignUuid('student_id')->constrained('students');
            $table->foreignId('period_id')->constrained('periods');
            $table->foreignUuid('doctor_id')->nullable()->constrained('doctors');
            $table->foreignId('location_id')->nullable()->constrained('locations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dcu');
    }
};
