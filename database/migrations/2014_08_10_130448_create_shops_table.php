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
            $table->decimal('totalisator_akhir', 10, 3)->default(0);
            $table->decimal('stik_akhir', 10, 2)->default(142.85);
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
