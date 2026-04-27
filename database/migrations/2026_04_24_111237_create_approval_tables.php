<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ══════════════════════════════════════════════════
        // TABEL 1: approval_steps
        // Template alur approval per jenis dokumen.
        // Contoh: 'surat' punya 4 step, 'absensi' cukup 1.
        // ══════════════════════════════════════════════════
        Schema::create('approval_steps', function (Blueprint $table) {
            $table->id();
            $table->string('document_type')->comment('Jenis dokumen: surat, purchase_requisition, dll');
            $table->tinyInteger('step_order')->unsigned()->comment('Urutan step: 1, 2, 3, 4');
            $table->string('jabatan')->comment('Jabatan yang bertanggung jawab: hod, purchasing, owner_rep, direktur');
            $table->string('label')->comment('Label tampilan: Head of Department, Purchasing, dst');
            $table->timestamps();

            $table->unique(['document_type', 'step_order'], 'unique_doc_step');
        });

        // ══════════════════════════════════════════════════
        // TABEL 2: document_approvals
        // Log approval per dokumen — satu baris per step per dokumen.
        // ══════════════════════════════════════════════════
        Schema::create('document_approvals', function (Blueprint $table) {
            $table->id();

            // Polymorphic — bisa untuk model dokumen apapun
            $table->string('document_type')->comment('Nama model: App\\Models\\Surat, dll');
            $table->unsignedBigInteger('document_id')->comment('ID dokumen yang di-approve');

            $table->tinyInteger('step_order')->unsigned()->comment('Step ke berapa');
            $table->string('jabatan')->comment('Jabatan yang harus approve step ini');
            $table->string('label')->comment('Label jabatan untuk tampilan');

            // Siapa yang approve / reject
            $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete();

            // Status step ini
            $table->enum('status', [
                'pending',   // menunggu, belum giliran
                'waiting',   // giliran step ini, menunggu aksi
                'approved',  // disetujui
                'rejected',  // ditolak → dokumen kembali ke staff
            ])->default('pending');

            $table->text('catatan')->nullable()->comment('Catatan dari approver');
            $table->timestamp('actioned_at')->nullable()->comment('Kapan aksi dilakukan');

            $table->timestamps();

            $table->index(['document_type', 'document_id'], 'idx_doc');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_approvals');
        Schema::dropIfExists('approval_steps');
    }
};