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
            // Make jml_saudara_tiri nullable
            $table->integer('jml_saudara_tiri')->nullable()->change();
            
            // Drop old combined income
            $table->dropColumn('penghasilan_ortu');
            
            // Add split income columns
            $table->decimal('penghasilan_ayah', 15, 2)->after('pekerjaan_ayah')->default(0);
            $table->decimal('penghasilan_ibu', 15, 2)->after('pekerjaan_ibu')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            $table->integer('jml_saudara_tiri')->nullable(false)->change();
            $table->dropColumn(['penghasilan_ayah', 'penghasilan_ibu']);
            $table->decimal('penghasilan_ortu', 15, 2)->default(0);
        });
    }
};
