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
        Schema::create('incomings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->cascadeOnDelete();
            $table->foreignId('purchase_id')->constrained();
            $table->foreignId('vendor_id')->constrained();
            $table->string('sopir');
            $table->string('no_polisi');
            $table->decimal('volume', 6, 2);
            $table->decimal('stik_sebelum_curah', 5, 2);
            $table->decimal('stik_setelah_curah', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomings');
    }
};
