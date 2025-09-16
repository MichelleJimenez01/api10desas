<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        Notification::create([
            'event_notification' => 'Nueva publicación creada',
            'publication_id' => 1,
        ]);

        Notification::create([
            'event_notification' => 'Tu publicación fue comentada',
            'publication_id' => 1,
        ]);

        Notification::create([
            'event_notification' => 'Se reportó un desastre en tu zona ⚠️',
            'publication_id' => 2,
        ]);
    }
}
