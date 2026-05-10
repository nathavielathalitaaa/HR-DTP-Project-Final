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
            $table->string('metode_ttd')->default('stamp')->change();
        });

        \DB::table('document_approvals')->where('metode_ttd', 'ttd_digital')->update(['metode_ttd' => 'stamp']);
        \DB::table('document_approvals')->where('metode_ttd', 'ttd_manual')->update(['metode_ttd' => 'append']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_approvals', function (Blueprint $table) {
            $table->string('metode_ttd')->default('ttd_digital')->change();
        });
    }
};
