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
        Schema::create('investor_profits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profit_sharing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('investor_shop_id')->constrained('investor_shop');
            $table->decimal('nilai_profit', 12, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investor_profits');
    }
};
