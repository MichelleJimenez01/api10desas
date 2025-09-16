<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->boolean('is_read')->default(false);

            // Relacionando con profiles.id
            $table->unsignedBigInteger('sender_profile_id');
            $table->unsignedBigInteger('receiver_profile_id');

            $table->foreign('sender_profile_id')
                  ->references('id')->on('profiles')
                  ->onDelete('cascade');

            $table->foreign('receiver_profile_id')
                  ->references('id')->on('profiles')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
