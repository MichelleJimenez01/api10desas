<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Ably\AblyRest;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::included()->filter()->sort()->paginateCustom();
        return response()->json($notifications);
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_notification' => 'required|string',
            'publication_id' => 'required|integer|exists:publications,publication_id',
        ]);

        $notification = Notification::create([
            'event_notification' => $request->event_notification,
            'publication_id' => $request->publication_id,
        ]);

        // 🔴 Publicar en Ably
        $ably = new AblyRest(config('services.ably.key'));
        $channel = $ably->channel('notifications');
        $channel->publish('new-notification', $notification);

        return response()->json($notification, 201);
    }

    public function show($id)
    {
        $notification = Notification::included()->findOrFail($id);
        return response()->json($notification);
    }

    public function update(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update($request->only(['event_notification']));

        return response()->json($notification);
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json(null, 204);
    }
}
