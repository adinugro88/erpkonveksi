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
        Schema::create('setortarikdtls', function (Blueprint $table) {
            $table->id();
            $table->string('barang');
            $table->integer('jumlah');
            $table->string('keterangan')->nullable();
            $table->foreignId('setortarik_id')->constrained('setortariks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setortarikdtls');
    }
};
