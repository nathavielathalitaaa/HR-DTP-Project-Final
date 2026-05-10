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
        Schema::create('surat_type_approvers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_type_id')->constrained()->cascadeOnDelete();
            $table->integer('urutan');        // 1,2,3,4
            $table->string('jabatan');        // HOD, Purchasing, Owner Rep, Direktur
            $table->string('label');          // Requested by, Checked by, Approved by
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_type_approvers');
    }
};
