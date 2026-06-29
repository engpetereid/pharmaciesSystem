<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationsController extends Controller
{

    public function index()
    {
        $notifications = Notification::with(['pharmacy', 'drug'])
            ->latest()
            ->paginate(50);

        return view('admin.notifications.index', compact('notifications'));
    }
}
