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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('student_id')->constrained('students')->onDelete('cascade');
            $table->string('day', 7);
            $table->date('date')->nullable();
            $table->time('arrival_time');
            $table->time('departure_time')->nullable();
            $table->text('complaint');
            $table->text('treatment')->nullable();
            $table->text('outcome_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
