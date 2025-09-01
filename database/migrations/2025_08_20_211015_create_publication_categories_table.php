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
        Schema::create('publication_categories', function (Blueprint $table) {
            $table->id();

             // Relación con publications (usando id estándar)
            $table->foreignId('publication_id')
                  ->constrained() // Laravel automáticamente usará el id estándar
                  ->cascadeOnDelete();
            
            // Relación con categories (usando id estándar)
            $table->foreignId('category_id')
                  ->constrained() // Laravel automáticamente usará el id estándar
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publication_categories');
    }
};
