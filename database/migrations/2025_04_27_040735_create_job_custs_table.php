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
        Schema::create('job_custs', function (Blueprint $table) {
            $table->id();
            $table->string("nama");
            $table->date("tanggal");
            $table->text("photo1");
            $table->text("photo2");
            $table->text("keterangan");
            $table->BigInteger('customers_id')->unsigned();
            $table->foreign('customers_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_custs');
    }
};
