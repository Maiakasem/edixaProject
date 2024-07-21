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
        $types = [
            'tinyInteger',
            'smallInteger',
            'mediumInteger',
            'integer',
            'bigInteger',
            'float',
            'decimal',
            'string',
            'tinyText',
            'text',
            'mediumText',
            'longText',
            'boolean',
            'date',
            'dateTime',
            'time',
            'timestamp',
            'year',
            'ipAddress',
            'json',
            'enum',
            'belongsTo',
            'belongsToMany',
        ];

        Schema::create('quick_maker_columns', function (Blueprint $table) use ($types){
            $table->id();

            $table->foreignId('quick_maker_id');
            $table->foreign('quick_maker_id')->references('id')->on('quick_makers')->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('name');
            $table->enum('type', $types);
            $table->string('label')->nullable();
            $table->boolean('required')->default(false);
            $table->boolean('unique')->default(false);
            $table->boolean('searchable')->default(false);
            $table->boolean('translatable')->default(false);
            $table->boolean('relation')->default(false);
            $table->string('relation_display')->nullable();
            $table->string('relation_model')->nullable();
            $table->string('relation_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_maker_columns');
    }
};
