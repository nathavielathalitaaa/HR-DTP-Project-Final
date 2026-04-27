<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // ── Identitas ──────────────────────────────
            $table->string('nik', 20)->nullable()->unique()->comment('NIK KTP 16 digit');
            $table->string('no_kk', 20)->nullable()->comment('Nomor Kartu Keluarga');
            $table->string('npwp', 20)->nullable()->comment('Nomor Pokok Wajib Pajak');
            $table->string('bpjs_kesehatan', 25)->nullable()->comment('No BPJS Kesehatan');
            $table->string('bpjs_ketenagakerjaan', 25)->nullable()->comment('No BPJS Ketenagakerjaan');

            // ── Kepegawaian ────────────────────────────
            $table->string('jabatan')->nullable()->comment('Jabatan: HOD, Purchasing, Owner Rep, Direktur, dll');
            $table->string('pendidikan_terakhir')->nullable()->comment('SD, SMP, SMA, D3, S1, S2, S3');
            $table->date('tgl_bergabung')->nullable()->comment('Tanggal mulai bekerja');
            $table->date('tgl_kontrak_akhir')->nullable()->comment('Tanggal berakhirnya kontrak');

            // ── Keluarga ───────────────────────────────
            $table->enum('status_pernikahan', ['belum_menikah', 'menikah', 'cerai_hidup', 'cerai_mati'])
                  ->default('belum_menikah');
            $table->tinyInteger('jumlah_anak')->default(0)->unsigned();

            // ── Kontak & Lokasi ────────────────────────
            $table->text('alamat')->nullable()->comment('Alamat lengkap sesuai KTP');
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos', 10)->nullable();

            // ── TTD & PIN untuk sistem approval ────────
            $table->string('ttd_path')->nullable()->comment('Path file tanda tangan');
            $table->string('pin', 255)->nullable()->comment('PIN approval (hashed bcrypt)');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};