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
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique');
            $table->dropColumn('email');
            $table->dropColumn('email_verified_at');
            $table->string('username')->unique();
            $table->tinyInteger('type')->default(0);
            $table->string('secret',200)->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->dropColumn('secret');
            $table->dropColumn('username');
            $table->dropColumn('type');
            $table->dropColumn('is_active');
            $table->unique('email'); 
        });
    }
};
