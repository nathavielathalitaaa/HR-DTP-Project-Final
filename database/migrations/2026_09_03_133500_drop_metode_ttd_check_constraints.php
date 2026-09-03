<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE surat_type_approvers DROP CONSTRAINT IF EXISTS surat_type_approvers_metode_ttd_check');
            DB::statement('ALTER TABLE document_approvals DROP CONSTRAINT IF EXISTS document_approvals_metode_ttd_check');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left blank as legacy constraints are deprecated.
    }
};
