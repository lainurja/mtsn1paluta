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
        Schema::table('data_pendaftars', function (Blueprint $table) {
            $table->json('pengembangan_diri')->nullable()->after('no_hp_ortu');
            $table->text('cita_cita')->nullable()->after('pengembangan_diri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            $table->dropColumn(['pengembangan_diri', 'cita_cita']);
        });
    }
};
