<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 50)->unique();
            $table->foreignId('cabang_asal_id')->constrained('cabang');
            $table->foreignId('cabang_tujuan_id')->constrained('cabang');
            $table->foreignId('box_id')->nullable()->constrained('boxes')->nullOnDelete();
            
            $table->string('nama_pengirim');
            $table->string('no_wa_pengirim', 30);
            $table->string('nama_penerima');
            $table->string('no_wa_penerima', 30);
            
            $table->decimal('berat_kg', 8, 2);
            $table->decimal('biaya_kirim', 12, 2);
            $table->decimal('fee_cabang', 12, 2);
            $table->string('kategori_cabang_snapshot', 10); // ESB or ELP
            
            $table->dateTime('tanggal_pengiriman');
            $table->dateTime('tanggal_sampai')->nullable();
            $table->dateTime('tanggal_diambil')->nullable();
            
            $table->enum('status', [
                'diterima_di_cabang',
                'dalam_perjalanan_ke_pusat',
                'disortir_di_pusat',
                'dalam_pengiriman_hk',
                'sampai_di_hk',
                'sudah_diambil'
            ])->default('diterima_di_cabang');
            
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
