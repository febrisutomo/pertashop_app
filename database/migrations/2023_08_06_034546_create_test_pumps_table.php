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
        Schema::create('test_pumps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->cascadeOnDelete();
            $table->unsignedDecimal('volume_test', 10, 2);
            $table->unsignedDecimal('volume_aktual', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_pumps');
    }
};
