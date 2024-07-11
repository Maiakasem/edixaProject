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
        Schema::create('quick_makers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('has_migration')->default(false);
            $table->boolean('has_model')->default(false);
            $table->boolean('has_blade')->default(false);
            $table->boolean('has_controller')->default(false);
            $table->boolean('has_module')->default(false);
            $table->string('module')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_makers');
    }
};
