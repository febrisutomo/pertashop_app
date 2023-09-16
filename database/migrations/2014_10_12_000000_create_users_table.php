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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['super-admin', 'admin', 'operator', 'investor'])->default('operator');
            $table->boolean('is_active', 1)->default(1);
            $table->string('no_hp')->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('pemilik_rekening')->nullable();
            $table->string('nama_bank')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
