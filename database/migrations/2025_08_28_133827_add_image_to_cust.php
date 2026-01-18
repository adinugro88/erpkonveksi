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
        Schema::table('job_custs', function (Blueprint $table) {
            $table->string('photo3')->nullable()->after('photo2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_custs', function (Blueprint $table) {
            $table->dropColumn('photo3');
        });
    }
};
