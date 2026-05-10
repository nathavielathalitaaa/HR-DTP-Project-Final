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
        // Drop unused tables
        Schema::dropIfExists('departments');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('interviews');
        Schema::dropIfExists('leave_information');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse for drop in this cleanup migration
    }
};
