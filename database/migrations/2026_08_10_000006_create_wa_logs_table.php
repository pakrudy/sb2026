<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wa_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->nullable()->constrained('barang')->nullOnDelete();
            $table->string('no_wa');
            $table->string('jenis_pesan');
            $table->text('isi_pesan');
            $table->enum('status_kirim', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('response_vendor')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wa_logs');
    }
};
