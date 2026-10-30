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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nis')->nullable()->unique()->after('email');
            $table->string('nip')->nullable()->unique()->after('nis');
            $table->string('phone')->nullable()->after('nip');
            $table->foreignId('kelas_id')->nullable()->after('phone')->constrained('kelas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kelas_id');
            $table->dropUnique(['nis']);
            $table->dropUnique(['nip']);
            $table->dropColumn(['nis', 'nip', 'phone']);
        });
    }
};
