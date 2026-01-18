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
        Schema::create('setortariks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_setortarik')->unique();
            $table->date('tanggal');
            $table->enum('jenis', ['setor', 'tarik']);
            $table->text('keterangan')->nullable();
            $table->text('photo1')->nullable();
            $table->text('photo2')->nullable();
            $table->text('photo3')->nullable();
            //mastersetortarik id
            $table->foreignId('mastersetortarik_id')->constrained('mastersetortariks')->onDelete
            ('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setortariks');
    }
};
