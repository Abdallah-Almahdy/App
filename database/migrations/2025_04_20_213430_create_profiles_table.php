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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->morphs('profilable');
            $table->string('image')->nullable();
            $table->string('bio')->nullable();
            $table->string('phone')->nullable();
            $table->string('linkedin')->nullable();
            $table->timestamps();

            // Set composite primary key
            $table->unique(['profilable_type', 'profilable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
