<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class NotificationService
{
    public static function listUserNotifications(User $user)
    {
        return $user->notifications()->latest()->get();
    }

    public static function sendNotification(Request $request)
    {
        // simpan dan broadcast ke receiver
    }
}
