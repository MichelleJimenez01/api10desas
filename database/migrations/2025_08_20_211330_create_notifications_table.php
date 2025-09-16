<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id'); // PK
            $table->string('event_notification'); // tipo o evento

            // Relación con publicaciones
            $table->unsignedBigInteger('publication_id'); // FK

            $table->timestamps();

            // Clave foránea
            $table->foreign('publication_id')->references('publication_id')->on('publications')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
