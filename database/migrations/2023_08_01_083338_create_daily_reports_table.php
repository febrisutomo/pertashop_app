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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained();
            $table->foreignId('operator_id')->constrained('users');
            $table->foreignId('price_id')->constrained();
            $table->unsignedDecimal('totalisator_akhir', 10, 3);
            $table->unsignedDecimal('stik_akhir', 5, 2)->nullable();
            $table->unsignedDecimal('setor_tunai', 12, 0);
            $table->unsignedDecimal('setor_qris', 12, 0);
            $table->unsignedDecimal('setor_transfer', 12, 0);
            $table->boolean('diverifikasi')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
