<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cabang', function (Blueprint $table) {
            $table->string('nama_pemilik', 150)->nullable()->after('nama_cabang');
            $table->string('no_telp_alternatif', 30)->nullable()->after('no_telp');
        });
    }

    public function down(): void
    {
        Schema::table('cabang', function (Blueprint $table) {
            $table->dropColumn(['nama_pemilik', 'no_telp_alternatif']);
        });
    }
};
