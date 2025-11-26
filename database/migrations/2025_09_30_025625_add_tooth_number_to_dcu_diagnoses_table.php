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
        Schema::table('dcu_diagnoses', function (Blueprint $table) {
            $table->string('tooth_number', 3)->after('dcu_id');
        });
    }

    public function down(): void
    {
        Schema::table('dcu_diagnoses', function (Blueprint $table) {
            $table->dropColumn('tooth_number');
        });
    }
};
