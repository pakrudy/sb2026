<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->id();
            $table->string('kode_box', 50)->unique();
            $table->foreignId('cabang_tujuan_id')->constrained('cabang');
            $table->enum('status', ['draft', 'dikemas', 'dalam_perjalanan', 'sampai_di_hk'])->default('draft');
            $table->dateTime('tanggal_dikemas')->nullable();
            $table->dateTime('tanggal_dikirim')->nullable();
            $table->dateTime('tanggal_sampai')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boxes');
    }
};
