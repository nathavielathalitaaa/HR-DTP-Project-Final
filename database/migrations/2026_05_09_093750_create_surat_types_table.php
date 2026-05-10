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
        Schema::create('surat_types', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // slug: izin, resign, pr
            $table->string('nama');           // Surat Izin, Surat Resign
            $table->text('deskripsi')->nullable();
            $table->json('nomor_format');     // array of komponen
            $table->integer('nomor_counter')->default(0);
            $table->enum('nomor_reset', ['never', 'yearly', 'monthly'])->default('yearly');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_types');
    }
};
