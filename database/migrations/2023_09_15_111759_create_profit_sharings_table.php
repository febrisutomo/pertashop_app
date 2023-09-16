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
        Schema::create('profit_sharings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained();
            $table->decimal('nilai_profit_sharing', 12, 0);
            $table->decimal('alokasi_modal', 12, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_sharings');
    }
};
