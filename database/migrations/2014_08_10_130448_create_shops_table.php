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
            $table->string('kode');
            $table->string('alamat');
            $table->decimal('stok_awal', 10, 2)->default(3000);
            $table->decimal('totalisator_awal', 10, 3)->default(0);
            $table->decimal('skala', 10, 2)->default(21);
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
