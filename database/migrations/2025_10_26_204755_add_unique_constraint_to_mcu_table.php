<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mcu', function (Blueprint $table) {
            $table->unique(['student_id', 'period_id'], 'mcu_student_period_unique');
        });
    }

    public function down(): void
    {
        Schema::table('mcu', function (Blueprint $table) {
            $table->dropUnique('mcu_student_period_unique');
        });
    }
};