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
        Schema::create('hasildetails', function (Blueprint $table) {
            $table->id();
            $table->text('deskripsi');
            $table->integer('jumlah');
            $table->bigInteger('harga')->nullable();
            $table->bigInteger('votes')->nullable();
            $table->bigInteger('total');
            $table->foreignId('pengahsilan_id')->constrained('penghasilans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasildetails');
    }
};
