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
         Schema::create('dcu_ohis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('dcu_id')->unique()->constrained('dcu')->onDelete('cascade');

        // DI Matrix (6 values)
        $table->decimal('di_1_1', 3, 1)->default(0);
        $table->decimal('di_1_2', 3, 1)->default(0);
        $table->decimal('di_1_3', 3, 1)->default(0);
        $table->decimal('di_2_1', 3, 1)->default(0);
        $table->decimal('di_2_2', 3, 1)->default(0);
        $table->decimal('di_2_3', 3, 1)->default(0);

        // CI Matrix (6 values)
        $table->decimal('ci_1_1', 3, 1)->default(0);
        $table->decimal('ci_1_2', 3, 1)->default(0);
        $table->decimal('ci_1_3', 3, 1)->default(0);
        $table->decimal('ci_2_1', 3, 1)->default(0);
        $table->decimal('ci_2_2', 3, 1)->default(0);
        $table->decimal('ci_2_3', 3, 1)->default(0);

        // Calculated Scores
        $table->decimal('di_score', 4, 2)->default(0); 
        $table->decimal('ci_score', 4, 2)->default(0); 
        $table->decimal('ohis_score', 4, 2)->default(0); 
        $table->enum('ohis_status', ['Baik', 'Sedang', 'Buruk'])->nullable();

        $table->text('notes')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dcu_ohis');
    }
};
