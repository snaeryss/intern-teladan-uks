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
        Schema::create('student_sync_histories', function (Blueprint $table) {
	        $table->integer('user_id');
            $table->id();
	        $table->Integer('new');
	        $table->Integer('skipped');
			$table->Integer('updated');
	        $table->boolean('sync_type')->default(false);
	        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_sync_histories');
    }
};
