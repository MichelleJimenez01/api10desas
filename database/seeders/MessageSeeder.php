<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        Message::create([
            'content' => 'Hola, ¿cómo estás?',
            'is_read' => false,
            'sender_profile_id' => 1,
            'receiver_profile_id' => 2,
        ]);

        Message::create([
            'content' => 'Todo bien, ¿y tú?',
            'is_read' => true,
            'sender_profile_id' => 2,
            'receiver_profile_id' => 1,
        ]);
    }
}
