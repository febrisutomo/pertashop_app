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
        Schema::create('rekap_modals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained();

            $table->unsignedDecimal('kas_kecil', 10, 2)->default(0);
            $table->unsignedDecimal('piutang', 10, 2)->default(0);

            $table->unsignedDecimal('rugi', 10, 2)->default(0);
            $table->unsignedDecimal('alokasi_keuntungan', 10, 2)->default(0);
            $table->unsignedDecimal('bunga_bank', 10, 2)->default(0);
            $table->unsignedDecimal('pajak_bank', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modals');
    }
};
