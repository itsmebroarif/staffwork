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
        Schema::table('materials', function (Blueprint $table) {
            $table->string('type')->default('artikel')->after('slug');
            $table->foreignId('kelas_id')->nullable()->after('type')->constrained('kelas')->nullOnDelete();
            $table->text('body')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->text('body')->nullable(false)->change();
            $table->dropConstrainedForeignId('kelas_id');
            $table->dropColumn('type');
        });
    }
};
