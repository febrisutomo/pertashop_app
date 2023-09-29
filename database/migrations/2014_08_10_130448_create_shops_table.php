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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('corporation_id')->constrained();
            $table->string('nama');
            $table->string('short_name')->nullable();
            $table->string('kode');
            $table->string('alamat');
            $table->unsignedDecimal('modal_awal', 12)->default(60000000);
            $table->unsignedDecimal('kapasitas', 10, 3)->default(3500);
            $table->unsignedDecimal('stik_awal', 10, 2)->default(166.66);
            $table->unsignedDecimal('totalisator_awal', 10, 3)->default(0);
            $table->unsignedDecimal('skala', 10, 2)->default(21);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
