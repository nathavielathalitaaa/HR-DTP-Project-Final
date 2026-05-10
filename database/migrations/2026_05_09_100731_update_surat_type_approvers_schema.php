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
        Schema::table('surat_type_approvers', function (Blueprint $table) {
            $table->renameColumn('jabatan', 'jabatan_label');
            $table->unsignedBigInteger('user_id')->nullable()->after('surat_type_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            // Changing enum values is tricky with standard Schema, using raw statement
            $table->string('metode_ttd')->default('stamp')->change();
        });

        // Update existing data to new enum strings
        \DB::table('surat_type_approvers')->where('metode_ttd', 'ttd_digital')->update(['metode_ttd' => 'stamp']);
        \DB::table('surat_type_approvers')->where('metode_ttd', 'ttd_manual')->update(['metode_ttd' => 'append']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_type_approvers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->renameColumn('jabatan_label', 'jabatan');
            $table->string('metode_ttd')->default('ttd_digital')->change();
        });
    }
};
