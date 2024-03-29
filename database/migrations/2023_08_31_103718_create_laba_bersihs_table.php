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
        Schema::create('laba_bersihs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained();
            $table->unsignedDecimal('laba_kotor', 12, 0);
            $table->unsignedDecimal('total_biaya', 12, 0);
            $table->unsignedDecimal('gaji_operator', 10, 0)->nullable();
            $table->unsignedDecimal('gaji_admin', 10, 0)->nullable();
            $table->unsignedDecimal('persentase_alokasi_modal', 10, 2)->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laba_bersihs');
    }
};
