<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dcu', function (Blueprint $table) {
            // 1. Hapus foreign key lama (ke doctors table)
            $table->dropForeign(['doctor_id']);
        });

        // 2. Kosongkan data doctor_id yang lama (karena tipe berbeda)
        DB::table('dcu')->update(['doctor_id' => null]);

        Schema::table('dcu', function (Blueprint $table) {
            // 3. Hapus kolom lama
            $table->dropColumn('doctor_id');
        });

        Schema::table('dcu', function (Blueprint $table) {
            // 4. Buat kolom baru dengan tipe BIGINT (sesuai users.id)
            $table->unsignedBigInteger('doctor_id')->nullable()->after('period_id');
            
            // 5. Buat foreign key ke users table
            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('dcu', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropColumn('doctor_id');
        });

        // Kembalikan ke UUID
        Schema::table('dcu', function (Blueprint $table) {
            $table->uuid('doctor_id')->nullable()->after('period_id');
            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('doctors')
                  ->onDelete('set null');
        });
    }
};