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
        $inputTypes = [
            "checkbox",
            "color",
            "date",
            "datetime-local",
            "email",
            "file",
            "image",
            "month",
            "number",
            "password",
            "tel",
            "text",
            "time",
            "url",
            "week"
        ];
        
        Schema::table('quick_maker_columns', function (Blueprint $table) use($inputTypes) {
            $table->enum('blade_type', $inputTypes)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quick_maker_columns', function (Blueprint $table) {
            $table->dropColumn('blade_type');
        });
    }
};
