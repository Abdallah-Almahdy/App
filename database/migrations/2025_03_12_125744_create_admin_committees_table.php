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
        Schema::create('admin_committees', function (Blueprint $table) {
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->foreignId('committee_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_committes');
    }
};
