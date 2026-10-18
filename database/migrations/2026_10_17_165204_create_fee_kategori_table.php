<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_kategori', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['ESB', 'ELP'])->unique();
            $table->decimal('fee_per_kg', 12, 2)->default(0);
            $table->string('keterangan', 200)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Seed data awal
        DB::table('fee_kategori')->insert([
            [
                'kategori'   => 'ESB',
                'fee_per_kg' => 1000.00,
                'keterangan' => 'Fee standar kategori ESB (Ekspedisi Sukses Bersama)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori'   => 'ELP',
                'fee_per_kg' => 2000.00,
                'keterangan' => 'Fee standar kategori ELP (Ekspedisi Luar Pulau)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_kategori');
    }
};
