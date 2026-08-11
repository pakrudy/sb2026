<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('cabang_id')->nullable()->after('id')->constrained('cabang')->nullOnDelete();
            $table->enum('role', ['super_admin', 'admin_cabang_indo', 'admin_pusat', 'admin_cabang_hk'])->default('admin_cabang_indo')->after('email');
            $table->string('no_hp', 30)->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('no_hp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropColumn(['cabang_id', 'role', 'no_hp', 'is_active']);
        });
    }
};
