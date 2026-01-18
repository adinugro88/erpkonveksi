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
        Schema::create('karjobs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pekerjaan');
            $table->string('deskripsi_pekerjaan')->nullable();
            $table->unsignedBigInteger('jobcust_id');
            $table->foreign('jobcust_id')->references('id')->on('job_custs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karjobs');
    }
};
