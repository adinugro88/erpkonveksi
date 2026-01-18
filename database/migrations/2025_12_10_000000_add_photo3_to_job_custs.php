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
        if (! Schema::hasTable('job_custs')) {
            return;
        }

        Schema::table('job_custs', function (Blueprint $table) {
            if (! Schema::hasColumn('job_custs', 'photo3')) {
                $table->string('photo3')->nullable()->after('photo2');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('job_custs')) {
            return;
        }

        Schema::table('job_custs', function (Blueprint $table) {
            if (Schema::hasColumn('job_custs', 'photo3')) {
                $table->dropColumn('photo3');
            }
        });
    }
};
