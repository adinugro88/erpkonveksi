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
        Schema::create('surat_jalans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sj')->unique();
            $table->date('tanggal');
            $table->foreignId('job_cust_id')->constrained('job_custs')->onDelete('cascade');
            $table->enum('jenis', ['keluar', 'masuk']);
            $table->string('penerima')->nullable();
            $table->string('pengirim')->nullable();
            $table->string('sopir')->nullable();
            $table->string('no_kendaraan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_jalans');
    }
};
