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
        Schema::create('setortarikpvts', function (Blueprint $table) {
            $table->id();
            //karjobs_id
            $table->foreignId('karjobs_id')->constrained('karjobs')->onDelete
            ('cascade');
            //setortariks_id
            $table->foreignId('setortarik_id')->constrained('setortariks')->onDelete
            ('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setortarikpvts');
    }
};
