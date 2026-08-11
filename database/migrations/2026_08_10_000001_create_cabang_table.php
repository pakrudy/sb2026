<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cabang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_cabang', 20)->unique();
            $table->string('nama_cabang');
            $table->enum('tipe_lokasi', ['indonesia', 'hongkong'])->default('indonesia');
            $table->enum('kategori_cabang', ['ESB', 'ELP', 'PUSAT', 'HK'])->default('ESB');
            $table->decimal('fee_per_kg', 12, 2)->default(1000.00);
            $table->text('alamat')->nullable();
            $table->string('no_telp', 30)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabang');
    }
};
