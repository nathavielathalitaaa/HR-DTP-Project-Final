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
        Schema::table('document_approvals', function (Blueprint $table) {
            // Simpan path TTD approver saat dia approve (snapshot)
            $table->string('ttd_snapshot')->nullable()->after('approver_id')
                  ->comment('Path TTD approver saat approval dilakukan');
            // Path PDF cover yang digenerate setelah semua approve
            $table->string('cover_pdf_path')->nullable()->after('ttd_snapshot')
                  ->comment('Path PDF cover approval final');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_approvals', function (Blueprint $table) {
            $table->dropColumn(['ttd_snapshot', 'cover_pdf_path']);
        });
    }
};
