<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah foreign key exists sebelum di-drop
        $foreignKeyExists = $this->foreignKeyExists('dcu', 'dcu_doctor_id_foreign');
        
        Schema::table('dcu', function (Blueprint $table) use ($foreignKeyExists) {
            // 1. Drop foreign key constraint lama (jika ada)
            if ($foreignKeyExists) {
                $table->dropForeign('dcu_doctor_id_foreign');
            }
            
            // 2. Drop kolom doctor_id lama (UUID)
            if (Schema::hasColumn('dcu', 'doctor_id')) {
                $table->dropColumn('doctor_id');
            }
        });

        Schema::table('dcu', function (Blueprint $table) {
            // 3. Tambah kolom doctor_id baru (BIGINT UNSIGNED)
            $table->unsignedBigInteger('doctor_id')->nullable()->after('period_id');
            
            // 4. Tambah foreign key ke users table (bukan doctors)
            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('dcu', function (Blueprint $table) {
            // Drop foreign key baru
            $table->dropForeign(['doctor_id']);
            $table->dropColumn('doctor_id');
        });

        Schema::table('dcu', function (Blueprint $table) {
            // Restore kolom doctor_id lama (UUID)
            $table->foreignUuid('doctor_id')->nullable()->after('period_id');
            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('doctors')
                  ->onDelete('set null');
        });
    }

    /**
     * Check if foreign key exists
     */
    private function foreignKeyExists(string $table, string $name): bool
    {
        $databaseName = DB::connection()->getDatabaseName();
        
        $result = DB::select(
            "SELECT CONSTRAINT_NAME 
             FROM information_schema.TABLE_CONSTRAINTS 
             WHERE TABLE_SCHEMA = ? 
             AND TABLE_NAME = ? 
             AND CONSTRAINT_NAME = ? 
             AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
            [$databaseName, $table, $name]
        );

        return count($result) > 0;
    }
};