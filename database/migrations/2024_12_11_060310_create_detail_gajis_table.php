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
        Schema::create('detail_gajis', function (Blueprint $table) {
            $table->id();
            $table->text('deskripsi');
            $table->integer('jumlah')->nullable();
            $table->integer('fee')->nullable();
            $table->integer('potongan')->nullable();
            $table->BigInteger('gaji_id')->unsigned();
            $table->foreign('gaji_id')->references('id')->on('gajis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_gajis');
    }
};
